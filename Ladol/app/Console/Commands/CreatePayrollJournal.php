<?php

namespace App\Console\Commands;

use App\Company;
use App\PayrollPolicy;
use Illuminate\Console\Command;
use App\PayrollJournal;
use App\Payroll;

class CreatePayrollJournal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payroll:create_journal {payroll_id} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $payroll=Payroll::find($this->argument('payroll_id'));
        $company=Company::find($payroll->company_id);
        $company_id=$company->id;
        if ($payroll) {
            $sections = \App\UserSection::where('company_id', $company_id)->with('users.user_groups')->get();
            $allusers = \App\User::where('company_id', $company_id)->with('user_groups')->get();
            // return ($allusers);

            $chart_of_accounts = \App\ChartOfAccount::where(['company_id' => $company_id, 'status' => 1,])->orderBy('position')->get();
            $payroll_details = $payroll->payroll_details;
            $specific_salary_component_types = \App\SpecificSalaryComponentType::where('company_id', $company_id)->get();
            $users = \App\User::where('company_id', $company_id)->with('user_groups')->get();
            $lsas = \App\LongServiceAward::where('company_id', $company_id)->orderBy('max_year', 'ASC')->get();
            $pp = PayrollPolicy::where('company_id', $company_id)->first();

            $days = cal_days_in_month(CAL_GREGORIAN, $payroll->month, $payroll->year);
            $date = date('Y-m-d', strtotime($payroll->year . '-' . $payroll->month . '-' . $days));

            $this->generate_journal($sections, $payroll, $chart_of_accounts, $payroll_details, $date, $specific_salary_component_types, $allusers, $pp, $lsas);
        }
        //
    }

    public function generate_journal($sections, $payroll, $chart_of_accounts, $payroll_details, $date, $specific_salary_component_types, $allusers, $pp, $lsas)
    {

        $data = '';
        $sn = 1;
        $section_total = [];
        $credit_total = 0;
        $debit_total = 0;
        foreach ($sections as $section) {
            $section_total[$section->id] = 0;
        }

        $npp = 0;


        foreach ($chart_of_accounts as $account):
            $filteredusers = $allusers;

            if ($account->uses_group == 1):


                $filteredusers = $allusers->filter(function ($filtereduser) use ($account) {

                    if ($filtereduser->user_groups->contains('id', $account->group_id)) {
                        return $filtereduser;
                    }

                });


            endif;
            if ($account->non_payroll_provision == 0):


                if ($account->nationality_display == 1) {
                    $users = $filteredusers->pluck('id');
                    $details = $payroll_details->whereIn('user_id', $users);
                } elseif ($account->nationality_display == 2) {

                    $users = $filteredusers->where('expat', 1)->pluck('id');
                    $details = $payroll_details->whereIn('user_id', $users);
                } elseif ($account->nationality_display == 3) {
                    $users = $filteredusers->where('expat', 0)->pluck('id');
                    $details = $payroll_details->whereIn('user_id', $users);
                }


                if ($account->display == 1):

                    if ($account->source == 1):
                        $account_sum = 0;
                        foreach ($details as $pdetail):


                            $scdetails = unserialize($pdetail->sc_details);


                            if (isset($scdetails['sc_allowances'][$account->salary_component_constant])):

                                // add the amount to the account sum
                                $account_sum += $scdetails['sc_allowances'][$account->salary_component_constant];


                            elseif (isset($scdetails['sc_deductions'][$account->salary_component_constant])):

                                // add the amount to the account sum
                                $account_sum += $scdetails['sc_deductions'][$account->salary_component_constant];

                            endif;
                            if ($account->account_extra_components):
                                foreach ($account->account_extra_components as $extra_component):

                                    //unserialize the salary component details
                                    $scdetails = unserialize($pdetail->sc_details);
                                    $psscdetails = unserialize($pdetail->ssc_details);

                                    if ($extra_component->source == 1 && $extra_component->source == 'addition'):

                                        if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $account_sum += $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                        elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $account_sum += $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif ($extra_component->source == 1 && $extra_component->operator == 'subtraction'):
                                        if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $account_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                        elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $account_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif ($extra_component->source == 2 && $extra_component->operator == 'addition'):

                                        $psscdetails = unserialize($pdetail->ssc_details);


                                        if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                            foreach ($specific_salary_component_types as $ct):
                                                if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                    foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                        if ($det == $ct->id):

                                                            $account_sum += $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif ($extra_component->source == 2 && $extra_component->operator == 'subtraction'):

                                        $psscdetails = unserialize($pdetail->ssc_details);


                                        if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                            foreach ($specific_salary_component_types as $ct):
                                                if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                    foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                        if ($det == $ct->id):

                                                            $account_sum -= $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif ($extra_component->source == 3 && $extra_component->operator == 'addition'):

                                        // add the amount to the account sum
                                        $const = $extra_component->payroll_constant;
                                        if ($const == 'gross_pay') {
                                            $account_sum += $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                        } elseif ($const == 'netpay') {
                                            $account_sum += $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                        } else {
                                            $account_sum += $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                        }
                                    //$account_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 3 && $extra_component->operator == 'subtraction'):

                                        // add the amount to the account sum
                                        $const = $extra_component->payroll_constant;
                                        if ($const == 'gross_pay') {
                                            $account_sum -= $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                        } elseif ($const == 'netpay') {
                                            $account_sum -= $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                        } else {
                                            $account_sum -= $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                        }
                                    //$account_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 4 && $extra_component->operator == 'addition'):

                                        // add the amount to the account sum


                                        $account_sum += $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                    //$account_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 4 && $extra_component->operator == 'subtraction'):

                                        // add the amount to the account sum
                                        $account_sum -= $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                    endif;

                                endforeach;
                            endif;
                        endforeach;
                        if ($account_sum > 0):
                            $journal=new PayrollJournal();
                            $journal->payroll_id=$payroll->id;
                            $journal->code='PAYROLL-' . date('ym', strtotime($date));
                            $journal->date=date('Y-m-d',strtotime($date));
                            $journal->gl_code=$account->code;
                            $journal->description=$account->description . '-' . date('ym', strtotime($date));

                            if ($account->type == 1):
                                $debit_total += $account->formula > 0 ? ($account_sum * $account->formula) / 100 : $account_sum;
                                if ($account->formula > 0):
                                    $journal->debit = (($account_sum * $account->formula) / 100);
                                else:
                                    $journal->debit= $account_sum;
                                endif;

                            elseif ($account->type == 0):
                                $credit_total += $account->formula > 0 ? ($account_sum * $account->formula) / 100 : $account_sum;

                                if ($account->formula > 0):
                                    $journal->credit= (($account_sum * $account->formula) / 100);
                                else:
                                    $journal->credit= $account_sum ;
                                endif;
                            endif;
                            $journal->save();

                        endif;
                    //display for salary components-
                    elseif ($account->source == 2):

                        $comp_sum = 0;

                        foreach ($details as $pdetail):

                            $psscdetails = unserialize($pdetail->ssc_details);
                            // $comp_sum=0;

                            if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                foreach ($specific_salary_component_types as $ct):
                                    if ($ct->id == $account->specific_salary_component_type_id):
                                        foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                            if ($det == $ct->id):

                                                $comp_sum += $psscdetails['ssc_amount'][$key];


                                            endif;

                                        endforeach;
                                    endif;
                                endforeach;
                            endif;
                            if ($account->account_extra_components):
                                foreach ($account->account_extra_components as $extra_component):

                                    //unserialize the salary component details
                                    $scdetails = unserialize($pdetail->sc_details);
                                    $psscdetails = unserialize($pdetail->ssc_details);

                                    if ($extra_component->source == 1 && $extra_component->source == 'addition'):

                                        if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $comp_sum += $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                        elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $comp_sum += $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif ($extra_component->source == 1 && $extra_component->operator == 'subtraction'):
                                        if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $comp_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                        elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $comp_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif ($extra_component->source == 2 && $extra_component->operator == 'addition'):

                                        $psscdetails = unserialize($pdetail->ssc_details);


                                        if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                            foreach ($specific_salary_component_types as $ct):
                                                if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                    foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                        // dd($psscdetails);

                                                        if ($det == $ct->id):

                                                            $comp_sum += $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif ($extra_component->source == 2 && $extra_component->operator == 'subtraction'):

                                        $psscdetails = unserialize($pdetail->ssc_details);


                                        if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                            foreach ($specific_salary_component_types as $ct):
                                                if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                    foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                        // dd($psscdetails);

                                                        if ($det == $ct->id):

                                                            $comp_sum -= $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif ($extra_component->source == 3 && $extra_component->operator == 'addition'):

                                        // add the amount to the account sum
                                        $const = $extra_component->payroll_constant;
                                        if ($const == 'gross_pay') {
                                            $comp_sum += $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                        } elseif ($const == 'netpay') {
                                            $comp_sum += $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                        } else {
                                            $comp_sum += $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                        }
                                    //$comp_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 3 && $extra_component->operator == 'subtraction'):

                                        // add the amount to the account sum
                                        $const = $extra_component->payroll_constant;
                                        if ($const == 'gross_pay') {
                                            $comp_sum -= $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                        } elseif ($const == 'netpay') {
                                            $comp_sum -= $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                        } else {
                                            $comp_sum -= $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                        }
                                    //$comp_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 4 && $extra_component->operator == 'addition'):

                                        // add the amount to the account sum


                                        $comp_sum += $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                    //$comp_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 4 && $extra_component->operator == 'subtraction'):

                                        // add the amount to the account sum
                                        $comp_sum -= $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                    endif;

                                endforeach;
                            endif;
                        endforeach;
                        if ($comp_sum > 0):

                            $journal=new PayrollJournal();
                            $journal->payroll_id=$payroll->id;
                            $journal->code='PAYROLL-' . date('ym', strtotime($date));
                            $journal->date=date('Y-m-d',strtotime($date));
                            $journal->gl_code=$account->code;
                            $journal->description=$account->description . '-' . date('ym', strtotime($date));

                            if ($account->type == 1):
                                $debit_total += $account->formula > 0 ? ($comp_sum * $account->formula) / 100 : $comp_sum;
                                if ($account->formula > 0):
                                    $journal->debit = (($comp_sum * $account->formula) / 100);
                                else:
                                    $journal->debit= $comp_sum ;
                                endif;

                            elseif ($account->type == 0):
                                $credit_total += $account->formula > 0 ? ($comp_sum * $account->formula) / 100 : $comp_sum;

                                if ($account->formula > 0):
                                    $journal->credit = (($comp_sum * $account->formula) / 100);
                                else:
                                    $journal->credit= $comp_sum ;
                                endif;
                            endif;
                            $journal->save();
                        endif;
                    //display for specific salry component type
                    elseif ($account->source == 3):

                        $account_sum = 0;

                        foreach ($details as $pdetail):
                            //check if there was an entry

                            // add the amount to the account sum
                            $const = $account->other_constant;
                            if ($const == 'gross_pay') {
                                $account_sum += $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                            } elseif ($const == 'netpay') {
                                $account_sum += ($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                            } else {
                                $account_sum += $pdetail->$const;
                            }
                            //$account_sum+=$pdetail->$const;

                            if ($account->account_extra_components):
                                foreach ($account->account_extra_components as $extra_component):

                                    //unserialize the salary component details
                                    $scdetails = unserialize($pdetail->sc_details);
                                    $psscdetails = unserialize($pdetail->ssc_details);

                                    if ($extra_component->source == 1 && $extra_component->source == 'addition'):

                                        if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $account_sum += $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                        elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $account_sum += $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif ($extra_component->source == 1 && $extra_component->operator == 'subtraction'):
                                        if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $account_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                        elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $account_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif ($extra_component->source == 2 && $extra_component->operator == 'addition'):

                                        $psscdetails = unserialize($pdetail->ssc_details);


                                        if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                            foreach ($specific_salary_component_types as $ct):
                                                if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                    foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                        // dd($psscdetails);

                                                        if ($det == $ct->id):

                                                            $account_sum += $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif ($extra_component->source == 2 && $extra_component->operator == 'subtraction'):

                                        $psscdetails = unserialize($pdetail->ssc_details);


                                        if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                            foreach ($specific_salary_component_types as $ct):
                                                if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                    foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                        // dd($psscdetails);

                                                        if ($det == $ct->id):

                                                            $account_sum -= $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif ($extra_component->source == 3 && $extra_component->operator == 'addition'):

                                        // add the amount to the account sum
                                        $const = $extra_component->payroll_constant;
                                        if ($const == 'gross_pay') {
                                            $account_sum += $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                        } elseif ($const == 'netpay') {
                                            $account_sum += $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                        } else {
                                            $account_sum += $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                        }
                                    //$account_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 3 && $extra_component->operator == 'subtraction'):

                                        // add the amount to the account sum
                                        $const = $extra_component->payroll_constant;
                                        if ($const == 'gross_pay') {
                                            $account_sum -= $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                        } elseif ($const == 'netpay') {
                                            $account_sum -= $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                        } else {
                                            $account_sum -= $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                        }
                                    //$account_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 4 && $extra_component->operator == 'addition'):

                                        // add the amount to the account sum


                                        $account_sum += $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                    //$account_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 4 && $extra_component->operator == 'subtraction'):

                                        // add the amount to the account sum
                                        $account_sum -= $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                    endif;
                                endforeach;
                            endif;
                        endforeach;
                        if ($account_sum > 0)
                            $journal=new PayrollJournal();
                            $journal->payroll_id=$payroll->id;
                            $journal->code='PAYROLL-' . date('ym', strtotime($date));
                            $journal->date=date('Y-m-d',strtotime($date));
                            $journal->gl_code=$account->code;
                            $journal->description=$account->description . '-' . date('ym', strtotime($date));
                        if ($account->type == 1):
                            $debit_total += $account->formula > 0 ? ($account_sum * $account->formula) / 100 : $account_sum;
                            if ($account->formula > 0):
                                $journal->debit= (($account_sum * $account->formula) / 100);
                            else:
                                $journal->debit= $account_sum;
                            endif;

                        elseif ($account->type == 0):
                            $credit_total += $account->formula > 0 ? ($account_sum * $account->formula) / 100 : $account_sum;

                            if ($account->formula > 0):
                                $journal->credit= (($account_sum * $account->formula) / 100);
                            else:
                                $journal->credit= $account_sum ;
                            endif;
                        endif;
                        $journal->save();
                    endif;
                endif;
            elseif ($account->display == 2):
                //display for spread

                foreach ($sections as $section):
                    $section_users = $section->users;
                    //Check if it is a non payroll provision
                    if ($account->uses_group == 1):


                        $section_users = $section->users->filter(function ($filtereduser) use ($account) {

                            if ($filtereduser->user_groups->contains('id', $account->group_id)) {
                                return $filtereduser;
                            }

                        });


                    endif;

                    if ($account->nationality_display == 1) {

                        $users = $section_users->pluck('id');
                        $details = $payroll_details->whereIn('user_id', $users);
                        $section_sum = 0;

                    } elseif ($account->nationality_display == 2) {
                        $users = $section_users->where('expat', 1)->pluck('id');
                        $details = $payroll_details->whereIn('user_id', $users);
                        $section_sum = 0;


                    } elseif ($account->nationality_display == 3) {
                        $users = $section_users->where('expat', 0)->pluck('id');
                        $details = $payroll_details->whereIn('user_id', $users);
                        $section_sum = 0;

                    } else {
                        $users = $section_users->pluck('id');
                        $details = $payroll_details->whereIn('user_id', $users);
                        $section_sum = 0;
                    }


                    if ($account->source == 1):

                        //loop through all office payroll details
                        foreach ($details as $pdetail):

                            //unserialize the salary component details
                            $scdetails = unserialize($pdetail->sc_details);

                            //check if the was an entry
                            if (isset($scdetails['sc_allowances'][$account->salary_component_constant])):

                                // add the amount to the account sum
                                $section_sum += $scdetails['sc_allowances'][$account->salary_component_constant];


                            elseif (isset($scdetails['sc_deductions'][$account->salary_component_constant])):

                                // add the amount to the account sum
                                $section_sum += $scdetails['sc_deductions'][$account->salary_component_constant];

                            endif;
                            if ($account->account_extra_components):
                                foreach ($account->account_extra_components as $extra_component):

                                    //unserialize the salary component details
                                    $scdetails = unserialize($pdetail->sc_details);
                                    $psscdetails = unserialize($pdetail->ssc_details);

                                    if ($extra_component->source == 1 && $extra_component->source == 'addition'):

                                        if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $section_sum += $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                        elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $section_sum += $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif ($extra_component->source == 1 && $extra_component->operator == 'subtraction'):
                                        if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):
                                            // add the amount to the account sum
                                            $section_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                        elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $section_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif ($extra_component->source == 2 && $extra_component->operator == 'addition'):

                                        $psscdetails = unserialize($pdetail->ssc_details);


                                        if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                            foreach ($specific_salary_component_types as $ct):
                                                if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                    foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                        // dd($psscdetails);

                                                        if ($det == $ct->id):

                                                            $section_sum += $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif ($extra_component->source == 2 && $extra_component->operator == 'subtraction'):

                                        $psscdetails = unserialize($pdetail->ssc_details);

                                        if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                            foreach ($specific_salary_component_types as $ct):
                                                if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                    foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                        // dd($psscdetails);

                                                        if ($det == $ct->id):

                                                            $section_sum -= $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif ($extra_component->source == 3 && $extra_component->operator == 'addition'):

                                        // add the amount to the account sum
                                        $const = $extra_component->payroll_constant;
                                        if ($const == 'gross_pay') {
                                            $section_sum += $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                        } elseif ($const == 'netpay') {
                                            $section_sum += $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                        } else {
                                            $section_sum += $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                        }
                                    //$section_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 3 && $extra_component->operator == 'subtraction'):

                                        // add the amount to the account sum
                                        $const = $extra_component->payroll_constant;
                                        if ($const == 'gross_pay') {
                                            $section_sum -= $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                        } elseif ($const == 'netpay') {
                                            $section_sum -= $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                        } else {
                                            $section_sum -= $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                        }
                                    //$section_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 4 && $extra_component->operator == 'addition'):
                                        // add the amount to the account sum
                                        $section_sum += $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;
                                    //$section_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 4 && $extra_component->operator == 'subtraction'):

                                        // add the amount to the account sum
                                        $section_sum -= $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                    endif;

                                endforeach;
                            endif;
                        endforeach;
                        if ($section_sum > 0):

                            $journal=new PayrollJournal();
                            $journal->payroll_id=$payroll->id;
                            $journal->code='PAYROLL-' . date('ym', strtotime($date));
                            $journal->date=date('Y-m-d',strtotime($date));
                            $journal->gl_code=$account->code;
                            $journal->description=$account->description . '-' . date('ym', strtotime($date));


                            if ($account->salary_charge == 'salary'):
                                $journal->project_code= $section->salary_project_code ;
                            elseif ($account->salary_charge == 'charge'):
                                $journal->project_code= $section->charge_project_code;
                            endif;

                            if ($account->type == 1):
                                $debit_total += $account->formula > 0 ? ($section_sum * $account->formula) / 100 : $section_sum;
                                if ($account->formula > 0):
                                    $journal->debit= (($section_sum * $account->formula) / 100);
                                else:
                                    $journal->debit= $section_sum ;
                                endif;

                            elseif ($account->type == 0):
                                $credit_total += $account->formula > 0 ? ($section_sum * $account->formula) / 100 : $section_sum;

                                if ($account->formula > 0):
                                   $journal->credit= (($section_sum * $account->formula) / 100);
                                else:
                                    $journal->credit= $section_sum;
                                endif;
                            endif;
                            $journal->save();
                        endif;
                    //display for salary components
                    elseif ($account->source == 2):

                        // $psscdetails=unserialize($pdetail->ssc_details);

                        $comp_sum = 0;

                        foreach ($details as $pdetail):

                            $psscdetails = unserialize($pdetail->ssc_details);

                            if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                foreach ($specific_salary_component_types as $ct):
                                    if ($ct->id == $account->specific_salary_component_type_id):
                                        foreach ($psscdetails['ssc_component_category'] as $key => $det):


                                            // dd($psscdetails);

                                            if ($det == $ct->id):

                                                $comp_sum += $psscdetails['ssc_amount'][$key];

                                            endif;

                                        endforeach;
                                    endif;
                                endforeach;
                            endif;
                            if ($account->account_extra_components):
                                foreach ($account->account_extra_components as $extra_component):

                                    //unserialize the salary component details
                                    $scdetails = unserialize($pdetail->sc_details);
                                    $psscdetails = unserialize($pdetail->ssc_details);

                                    if ($extra_component->source == 1 && $extra_component->source == 'addition'):

                                        if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $comp_sum += $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                        elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $comp_sum += $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif ($extra_component->source == 1 && $extra_component->operator == 'subtraction'):
                                        if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $comp_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                        elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $comp_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif ($extra_component->source == 2 && $extra_component->operator == 'addition'):

                                        $psscdetails = unserialize($pdetail->ssc_details);

                                        if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                            foreach ($specific_salary_component_types as $ct):
                                                if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                    foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                        // dd($psscdetails);

                                                        if ($det == $ct->id):

                                                            $comp_sum += $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif ($extra_component->source == 2 && $extra_component->operator == 'subtraction'):

                                        $psscdetails = unserialize($pdetail->ssc_details);


                                        if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                            foreach ($specific_salary_component_types as $ct):
                                                if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                    foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                        // dd($psscdetails);

                                                        if ($det == $ct->id):

                                                            $comp_sum -= $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif ($extra_component->source == 3 && $extra_component->operator == 'addition'):

                                        // add the amount to the account sum
                                        $const = $extra_component->payroll_constant;
                                        if ($const == 'gross_pay') {
                                            $comp_sum += $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                        } elseif ($const == 'netpay') {
                                            $comp_sum += $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                        } else {
                                            $comp_sum += $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                        }
                                    //$comp_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 3 && $extra_component->operator == 'subtraction'):

                                        // add the amount to the account sum
                                        $const = $extra_component->payroll_constant;
                                        if ($const == 'gross_pay') {
                                            $comp_sum -= $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                        } elseif ($const == 'netpay') {
                                            $comp_sum -= $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                        } else {
                                            $comp_sum -= $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                        }
                                    //$comp_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 4 && $extra_component->operator == 'addition'):

                                        // add the amount to the account sum


                                        $comp_sum += $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                    //$comp_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 4 && $extra_component->operator == 'subtraction'):

                                        // add the amount to the account sum
                                        $comp_sum -= $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                    endif;

                                endforeach;
                            endif;
                        endforeach;
                        if ($comp_sum > 0):


                            $journal=new PayrollJournal();
                            $journal->payroll_id=$payroll->id;
                            $journal->code='PAYROLL-' . date('ym', strtotime($date));
                            $journal->date=date('Y-m-d',strtotime($date));
                            $journal->gl_code=$account->code;
                            $journal->description=$account->description . '-' . date('ym', strtotime($date));


                            if ($account->salary_charge == 'salary'):
                                $journal->project_code= $section->salary_project_code ;
                            elseif ($account->salary_charge == 'charge'):
                                $journal->project_code= $section->charge_project_code;
                            endif;

                            if ($account->type == 1):
                                $debit_total += $account->formula > 0 ? ($comp_sum * $account->formula) / 100 : $comp_sum;
                                if ($account->formula > 0):
                                    $journal->debit= (($comp_sum * $account->formula) / 100);
                                else:
                                    $journal->debit= $comp_sum ;
                                endif;

                            elseif ($account->type == 0):
                                $credit_total += $account->formula > 0 ? ($comp_sum * $account->formula) / 100 : $comp_sum;

                                if ($account->formula > 0):
                                   $journal->credit = (($comp_sum * $account->formula) / 100);
                                else:
                                    $journal->credit= $comp_sum ;
                                endif;
                            endif;
                            $journal->save();
                        endif;
                    //display for specific salry component type
                    elseif ($account->source == 3):

                        $section_sum = 0;

                        foreach ($details as $pdetail):
                            //check if the was an entry

                            // add the amount to the account sum
                            $const = $account->other_constant;
                            if ($const == 'gross_pay') {
                                $section_sum += $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                            } elseif ($const == 'netpay') {
                                $section_sum += $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                            } else {
                                $section_sum += $pdetail->$const;
                            }
                            //$section_sum+=$pdetail->$const;
                            if ($account->account_extra_components):
                                foreach ($account->account_extra_components as $extra_component):

                                    //unserialize the salary component details
                                    $scdetails = unserialize($pdetail->sc_details);
                                    $psscdetails = unserialize($pdetail->ssc_details);

                                    if ($extra_component->source == 1 && $extra_component->source == 'addition'):

                                        if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $section_sum += $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                        elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $section_sum += $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif ($extra_component->source == 1 && $extra_component->operator == 'subtraction'):
                                        if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $section_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                        elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $section_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif ($extra_component->source == 2 && $extra_component->operator == 'addition'):

                                        $psscdetails = unserialize($pdetail->ssc_details);


                                        if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                            foreach ($specific_salary_component_types as $ct):
                                                if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                    foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                        // dd($psscdetails);

                                                        if ($det == $ct->id):

                                                            $section_sum += $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif ($extra_component->source == 2 && $extra_component->operator == 'subtraction'):

                                        $psscdetails = unserialize($pdetail->ssc_details);


                                        if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                            foreach ($specific_salary_component_types as $ct):
                                                if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                    foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                        // dd($psscdetails);

                                                        if ($det == $ct->id):

                                                            $section_sum -= $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif ($extra_component->source == 3 && $extra_component->operator == 'addition'):

                                        // add the amount to the account sum
                                        $const = $extra_component->payroll_constant;
                                        if ($const == 'gross_pay') {
                                            $section_sum += $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                        } elseif ($const == 'netpay') {
                                            $section_sum += $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                        } else {
                                            $section_sum += $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                        }
                                    //$account_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 3 && $extra_component->operator == 'subtraction'):

                                        // add the amount to the account sum
                                        $const = $extra_component->payroll_constant;
                                        if ($const == 'gross_pay') {
                                            $section_sum -= $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                        } elseif ($const == 'netpay') {
                                            $section_sum -= $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                        } else {
                                            $section_sum -= $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                        }
                                    //$account_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 4 && $extra_component->operator == 'addition'):

                                        // add the amount to the account sum


                                        $section_sum += $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                    //$account_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 4 && $extra_component->operator == 'subtraction'):

                                        // add the amount to the account sum
                                        $section_sum -= $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                    endif;

                                endforeach;
                            endif;
                        endforeach;
                        if ($section_sum > 0):
                            $journal=new PayrollJournal();
                            $journal->payroll_id=$payroll->id;
                            $journal->code='PAYROLL-' . date('ym', strtotime($date));
                            $journal->date=date('Y-m-d',strtotime($date));
                            $journal->gl_code=$account->code;
                            $journal->description=$account->description . '-'.$section->name.' -' . date('ym', strtotime($date));


                            if ($account->salary_charge == 'salary'):
                                $journal->project_code= $section->salary_project_code ;
                            elseif ($account->salary_charge == 'charge'):
                                $journal->project_code= $section->charge_project_code;
                            endif;

                            if ($account->type == 1):
                                $debit_total += $account->formula > 0 ? ($section_sum * $account->formula) / 100 : $section_sum;
                                if ($account->formula > 0):
                                   $journal->debit= (($section_sum * $account->formula) / 100) ;
                                else:
                                    $journal->debit= $section_sum ;
                                endif;

                            elseif ($account->type == 0):
                                $credit_total += $account->formula > 0 ? ($section_sum * $account->formula) / 100 : $section_sum;

                                if ($account->formula > 0):
                                    $journal->credit= (($section_sum * $account->formula) / 100) ;
                                else:
                                    $journal->credit= $section_sum ;
                                endif;
                            endif;
                            $journal->save();
                        endif;
                    endif;
                endforeach;
            //display for all
            //display for expatriates
            //display for nationals
            elseif ($account->display == 3):
                //display for individual
                $filteredusers = $allusers;
                //Check if it is a non payroll provision
                if ($account->uses_group == 1):


                    $filteredusers = $allusers->filter(function ($filtereduser) use ($account) {

                        if ($filtereduser->user_groups->contains('id', $account->group_id)) {
                            return $filtereduser;
                        }

                    });


                endif;

                //Check display type


                if ($account->nationality_display == 1) {
                    $users = $filteredusers->pluck('id');
                    $details = $payroll_details->whereIn('user_id', $users);
                } elseif ($account->nationality_display == 2) {

                    $users = $filteredusers->where('expat', 1)->pluck('id');
                    $details = $payroll_details->whereIn('user_id', $users);
                } elseif ($account->nationality_display == 3) {
                    $users = $filteredusers->where('expat', 0)->pluck('id');
                    $details = $payroll_details->whereIn('user_id', $users);
                }

                foreach ($users as $user):

                    $pdetail = $details->firstWhere('user_id', $user);
                    $account_sum = 0;

                    if ($pdetail):
                        if ($account->source == 1):

                            //unserialize the salary component details
                            $scdetails = unserialize($pdetail->sc_details);

                            // check if the was an entry
                            if (isset($scdetails['sc_allowances'][$account->salary_component_constant])):

                                // add the amount to the account sum
                                $account_sum = $scdetails['sc_allowances'][$account->salary_component_constant];


                            elseif (isset($scdetails['sc_deductions'][$account->salary_component_constant])):

                                // add the amount to the account sum
                                $account_sum = $scdetails['sc_deductions'][$account->salary_component_constant];

                            endif;
                            if ($account->account_extra_components):
                                foreach ($account->account_extra_components as $extra_component):

                                    //unserialize the salary component details
                                    $scdetails = unserialize($pdetail->sc_details);
                                    $psscdetails = unserialize($pdetail->ssc_details);

                                    if ($extra_component->source == 1 && $extra_component->source == 'addition'):

                                        if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $account_sum += $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                        elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $account_sum += $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif ($extra_component->source == 1 && $extra_component->operator == 'subtraction'):
                                        if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $account_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];
                                        elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $account_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif ($extra_component->source == 2 && $extra_component->operator == 'addition'):

                                        $psscdetails = unserialize($pdetail->ssc_details);

                                        if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                            foreach ($specific_salary_component_types as $ct):
                                                if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                    foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                        // dd($psscdetails);

                                                        if ($det == $ct->id):

                                                            $account_sum += $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif ($extra_component->source == 2 && $extra_component->operator == 'subtraction'):
                                        $psscdetails = unserialize($pdetail->ssc_details);
                                        if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                            foreach ($specific_salary_component_types as $ct):
                                                if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                    foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                        // dd($psscdetails);

                                                        if ($det == $ct->id):

                                                            $account_sum -= $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif ($extra_component->source == 3 && $extra_component->operator == 'addition'):

                                        // add the amount to the account sum
                                        $const = $extra_component->payroll_constant;
                                        if ($const == 'gross_pay') {
                                            $account_sum += $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                        } elseif ($const == 'netpay') {
                                            $account_sum += $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                        } else {
                                            $account_sum += $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                        }
                                    //$account_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 3 && $extra_component->operator == 'subtraction'):

                                        // add the amount to the account sum
                                        $const = $extra_component->payroll_constant;
                                        if ($const == 'gross_pay') {
                                            $account_sum -= $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                        } elseif ($const == 'netpay') {
                                            $account_sum -= $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                        } else {
                                            $account_sum -= $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                        }
                                    //$account_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 4 && $extra_component->operator == 'addition'):

                                        // add the amount to the account sum
                                        $account_sum += $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;
                                    //$account_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 4 && $extra_component->operator == 'subtraction'):

                                        // add the amount to the account sum
                                        $account_sum -= $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                    endif;

                                endforeach;
                            endif;
                            if ($account_sum > 0):

                                $journal=new PayrollJournal();
                                $journal->payroll_id=$payroll->id;
                                $journal->code='PAYROLL-' . date('ym', strtotime($date));
                                $journal->date=date('Y-m-d',strtotime($date));
                                $journal->gl_code=$account->code;
                                $journal->description=$account->description . '-' . date('ym', strtotime($date)) . '-' . (isset($pdetail->user->name) ? $pdetail->user->name : '');


                                if ($account->salary_charge == 'salary'):
                                    $journal->project_code= $section->salary_project_code ;
                                elseif ($account->salary_charge == 'charge'):
                                    $journal->project_code= $section->charge_project_code;
                                endif;


                                if ($account->type == 1):
                                    $debit_total += $account->formula > 0 ? ($account_sum * $account->formula) / 100 : $account_sum;
                                    if ($account->formula > 0):
                                        $journal->debit= (($account_sum * $account->formula) / 100) ;
                                    else:
                                        $journal->debit= $account_sum;
                                    endif;

                                elseif ($account->type == 0):
                                    $credit_total += $account->formula > 0 ? ($account_sum * $account->formula) / 100 : $account_sum;

                                    if ($account->formula > 0):
                                        $journal->credit= (($account_sum * $account->formula) / 100) ;
                                    else:
                                        $journal->credit= $account_sum ;
                                    endif;
                                endif;
                                $journal->save();
                            endif;
                        elseif ($account->source == 2):

                            $psscdetails = unserialize($pdetail->ssc_details);

                            $individual_sum = 0;

                            if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                foreach ($specific_salary_component_types as $ct):
                                    if ($ct->id == $account->specific_salary_component_type_id):
                                        foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                            // dd($psscdetails);

                                            if ($det == $ct->id):

                                                $individual_sum += $psscdetails['ssc_amount'][$key];
                                            endif;

                                        endforeach;
                                    endif;
                                endforeach;
                                if ($account->account_extra_components):
                                    foreach ($account->account_extra_components as $extra_component):

                                        //unserialize the salary component details
                                        $scdetails = unserialize($pdetail->sc_details);
                                        $psscdetails = unserialize($pdetail->ssc_details);

                                        if ($extra_component->source == 1 && $extra_component->source == 'addition'):

                                            if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                                // add the amount to the account sum
                                                $individual_sum += $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];

                                            elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                                // add the amount to the account sum
                                                $individual_sum += $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                            endif;
                                        elseif ($extra_component->source == 1 && $extra_component->operator == 'subtraction'):
                                            if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                                // add the amount to the account sum
                                                $individual_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];

                                            elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                                // add the amount to the account sum
                                                $individual_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                            endif;
                                        elseif ($extra_component->source == 2 && $extra_component->operator == 'addition'):

                                            $psscdetails = unserialize($pdetail->ssc_details);
                                            if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                                foreach ($specific_salary_component_types as $ct):
                                                    if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                        foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                            // dd($psscdetails);

                                                            if ($det == $ct->id):

                                                                $individual_sum += $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                            endif;

                                                        endforeach;
                                                    endif;
                                                endforeach;
                                            endif;
                                        elseif ($extra_component->source == 2 && $extra_component->operator == 'subtraction'):

                                            $psscdetails = unserialize($pdetail->ssc_details);

                                            if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                                foreach ($specific_salary_component_types as $ct):
                                                    if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                        foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                            if ($det == $ct->id):

                                                                $individual_sum -= $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                            endif;

                                                        endforeach;
                                                    endif;
                                                endforeach;
                                            endif;
                                        elseif ($extra_component->source == 3 && $extra_component->operator == 'addition'):

                                            // add the amount to the account sum
                                            $const = $extra_component->payroll_constant;
                                            if ($const == 'gross_pay') {
                                                $individual_sum += $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                            } elseif ($const == 'netpay') {
                                                $individual_sum += $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                            } else {
                                                $individual_sum += $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                            }
                                        //$individual_sum+=$pdetail->$const;

                                        elseif ($extra_component->source == 3 && $extra_component->operator == 'subtraction'):

                                            // add the amount to the account sum
                                            $const = $extra_component->payroll_constant;
                                            if ($const == 'gross_pay') {
                                                $individual_sum -= $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                            } elseif ($const == 'netpay') {
                                                $individual_sum -= $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                            } else {
                                                $individual_sum -= $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                            }
                                        //$individual_sum+=$pdetail->$const;

                                        elseif ($extra_component->source == 4 && $extra_component->operator == 'addition'):

                                            // add the amount to the account sum
                                            $individual_sum += $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;
                                        //$individual_sum+=$pdetail->$const;

                                        elseif ($extra_component->source == 4 && $extra_component->operator == 'subtraction'):
                                            // add the amount to the account sum
                                            $individual_sum -= $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                        endif;

                                    endforeach;
                                endif;
                                if ($individual_sum > 0):
                                    $journal=new PayrollJournal();
                                    $journal->payroll_id=$payroll->id;
                                    $journal->code='PAYROLL-' . date('ym', strtotime($date));
                                    $journal->date=date('Y-m-d',strtotime($date));
                                    $journal->gl_code=$account->code;
                                    $journal->description=$account->description . '-' . date('ym', strtotime($date)) . '-' . (isset($pdetail->user->name) ? $pdetail->user->name : '');


                                    if ($account->salary_charge == 'salary'):
                                        $journal->project_code= $section->salary_project_code ;
                                    elseif ($account->salary_charge == 'charge'):
                                        $journal->project_code= $section->charge_project_code;
                                    endif;


                                    if ($account->type == 1):
                                        $debit_total += $account->formula > 0 ? ($account_sum * $account->formula) / 100 : $account_sum;
                                        if ($account->formula > 0):
                                            $journal->debit= (($individual_sum * $account->formula) / 100) ;
                                        else:
                                            $journal->debit= $individual_sum;
                                        endif;

                                    elseif ($account->type == 0):
                                        $credit_total += $account->formula > 0 ? ($account_sum * $account->formula) / 100 : $account_sum;

                                        if ($account->formula > 0):
                                            $journal->credit= (($individual_sum * $account->formula) / 100) ;
                                        else:
                                            $journal->credit= $individual_sum ;
                                        endif;
                                    endif;
                                    $journal->save();

                                endif;
                            else:

                            endif;
                        elseif ($account->source == 3):

                            $account_sum = 0;


                            // add the amount to the account sum
                            $const = $account->other_constant;
                            if ($const == 'gross_pay') {
                                $account_sum += $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                            } elseif ($const == 'netpay') {
                                $account_sum += $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                            } else {
                                $account_sum += $pdetail->$const;
                            }
                            //$account_sum=$pdetail->$const;
                            if ($account->account_extra_components):
                                foreach ($account->account_extra_components as $extra_component):

                                    //unserialize the salary component details
                                    $scdetails = unserialize($pdetail->sc_details);
                                    $psscdetails = unserialize($pdetail->ssc_details);

                                    if ($extra_component->source == 1 && $extra_component->source == 'addition'):

                                        if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $account_sum += $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                        elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $account_sum += $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif ($extra_component->source == 1 && $extra_component->operator == 'subtraction'):
                                        if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $account_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                        elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                            // add the amount to the account sum
                                            $account_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                        endif;
                                    elseif ($extra_component->source == 2 && $extra_component->operator == 'addition'):

                                        $psscdetails = unserialize($pdetail->ssc_details);


                                        if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                            foreach ($specific_salary_component_types as $ct):
                                                if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                    foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                        // dd($psscdetails);

                                                        if ($det == $ct->id):

                                                            $account_sum += $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif ($extra_component->source == 2 && $extra_component->operator == 'subtraction'):

                                        $psscdetails = unserialize($pdetail->ssc_details);


                                        if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                            foreach ($specific_salary_component_types as $ct):
                                                if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                    foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                        // dd($psscdetails);

                                                        if ($det == $ct->id):

                                                            $account_sum -= $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                        endif;

                                                    endforeach;
                                                endif;
                                            endforeach;
                                        endif;
                                    elseif ($extra_component->source == 3 && $extra_component->operator == 'addition'):

                                        // add the amount to the account sum
                                        $const = $extra_component->payroll_constant;
                                        if ($const == 'gross_pay') {
                                            $account_sum += $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                        } elseif ($const == 'netpay') {
                                            $account_sum += $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                        } else {
                                            $account_sum += $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                        }
                                    //$account_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 3 && $extra_component->operator == 'subtraction'):

                                        // add the amount to the account sum
                                        $const = $extra_component->payroll_constant;
                                        if ($const == 'gross_pay') {
                                            $account_sum -= $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                        } elseif ($const == 'netpay') {
                                            $account_sum -= $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                        } else {
                                            $account_sum -= $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                        }
                                    //$account_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 4 && $extra_component->operator == 'addition'):

                                        // add the amount to the account sum


                                        $account_sum += $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                    //$account_sum+=$pdetail->$const;

                                    elseif ($extra_component->source == 4 && $extra_component->operator == 'subtraction'):

                                        // add the amount to the account sum
                                        $account_sum -= $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                    endif;

                                endforeach;
                            endif;

                            $journal=new PayrollJournal();
                            $journal->payroll_id=$payroll->id;
                            $journal->code='PAYROLL-' . date('ym', strtotime($date));
                            $journal->date=date('Y-m-d',strtotime($date));
                            $journal->gl_code=$account->code;
                            $journal->description=$account->description . '-' . date('ym', strtotime($date)) . '-' . (isset($pdetail->user->name) ? $pdetail->user->name : '');


                            if ($account->salary_charge == 'salary'):
                                $journal->project_code= $section->salary_project_code ;
                            elseif ($account->salary_charge == 'charge'):
                                $journal->project_code= $section->charge_project_code;
                            endif;


                            if ($account->type == 1):
                                $debit_total += $account->formula > 0 ? ($account_sum * $account->formula) / 100 : $account_sum;
                                if ($account->formula > 0):
                                    $journal->debit= (($individual_sum * $account->formula) / 100) ;
                                else:
                                    $journal->debit= $individual_sum;
                                endif;

                            elseif ($account->type == 0):
                                $credit_total += $account->formula > 0 ? ($account_sum * $account->formula) / 100 : $account_sum;

                                if ($account->formula > 0):
                                    $journal->credit= (($individual_sum * $account->formula) / 100) ;
                                else:
                                    $journal->credit= $individual_sum ;
                                endif;
                            endif;
                            $journal->save();



                        endif;
                    endif;

                endforeach;


            elseif ($account->non_payroll_provision == 1):


                foreach ($sections as $section):
                    $section_users = $section->users;
                    // Check if it is a non payroll provision
                    if ($account->uses_group == 1):


                        $section_users = $section->users->filter(function ($filtereduser) use ($account) {

                            if ($filtereduser->user_groups->contains('id', $account->group_id)) {
                                return $filtereduser;
                            }

                        });


                    endif;


                    $users = $section_users->where('expat', 0)->pluck('id');
                    $details = $payroll_details->whereIn('user_id', $users);
                    $section_sum = 0;

                    foreach ($details as $pdetail):
                        // check if the was an entry

                        // add the amount to the account sum

                        // add the amount to the account sum
                        $const = $account->other_constant;
                        if ($const == 'gross_pay') {
                            $section_sum += $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                        } elseif ($const == 'netpay') {
                            $section_sum += $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                        } else {
                            $section_sum += $pdetail->$const;
                        }
                        //$section_sum+=$pdetail->$const;
                        if ($account->account_extra_components):
                            foreach ($account->account_extra_components as $extra_component):

                                //unserialize the salary component details
                                $scdetails = unserialize($pdetail->sc_details);
                                $psscdetails = unserialize($pdetail->ssc_details);

                                if ($extra_component->source == 1 && $extra_component->source == 'addition'):

                                    if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $section_sum += $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                    elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $section_sum += $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                    endif;
                                elseif ($extra_component->source == 1 && $extra_component->operator == 'subtraction'):
                                    if (isset($scdetails['sc_allowances'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $section_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_allowances'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_allowances'][$extra_component->salary_component_constant];


                                    elseif (isset($scdetails['sc_deductions'][$extra_component->salary_component_constant])):

                                        // add the amount to the account sum
                                        $section_sum -= $extra_component->percentage > 0 ? ($scdetails['sc_deductions'][$extra_component->salary_component_constant] * $extra_component->percentage) / 100 : $scdetails['sc_deductions'][$extra_component->salary_component_constant];

                                    endif;
                                elseif ($extra_component->source == 2 && $extra_component->operator == 'addition'):

                                    $psscdetails = unserialize($pdetail->ssc_details);


                                    if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                        foreach ($specific_salary_component_types as $ct):
                                            if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                    // dd($psscdetails);

                                                    if ($det == $ct->id):

                                                        $section_sum += $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                    endif;

                                                endforeach;
                                            endif;
                                        endforeach;
                                    endif;
                                elseif ($extra_component->source == 2 && $extra_component->operator == 'subtraction'):

                                    $psscdetails = unserialize($pdetail->ssc_details);


                                    if ($pdetail->ssc_allowances > 0 || $pdetail->ssc_deductions > 0):
                                        foreach ($specific_salary_component_types as $ct):
                                            if ($ct->id == $extra_component->specific_salary_component_type_id):
                                                foreach ($psscdetails['ssc_component_category'] as $key => $det):

                                                    // dd($psscdetails);

                                                    if ($det == $ct->id):

                                                        $section_sum -= $extra_component->percentage > 0 ? ($psscdetails['ssc_amount'][$key] * $extra_component->percentage) / 100 : $psscdetails['ssc_amount'][$key];

                                                    endif;

                                                endforeach;
                                            endif;
                                        endforeach;
                                    endif;
                                elseif ($extra_component->source == 3 && $extra_component->operator == 'addition'):

                                    // add the amount to the account sum
                                    $const = $extra_component->payroll_constant;
                                    if ($const == 'gross_pay') {
                                        $section_sum += $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                    } elseif ($const == 'netpay') {
                                        $section_sum += $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                    } else {
                                        $section_sum += $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                    }
                                //$account_sum+=$pdetail->$const;

                                elseif ($extra_component->source == 3 && $extra_component->operator == 'subtraction'):

                                    // add the amount to the account sum
                                    $const = $extra_component->payroll_constant;
                                    if ($const == 'gross_pay') {
                                        $section_sum -= $extra_component->percentage > 0 ? (($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances;
                                    } elseif ($const == 'netpay') {
                                        $section_sum -= $extra_component->percentage > 0 ? ((($pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances) - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues)) * $extra_component->percentage) / 100 : $pdetail->basic_pay + $pdetail->sc_allowances + $pdetail->ssc_allowances - ($pdetail->sc_deductions + $pdetail->ssc_deductions + $pdetail->paye + $pdetail->union_dues);
                                    } else {
                                        $section_sum -= $extra_component->percentage > 0 ? ($pdetail->$const * $extra_component->percentage) / 100 : $pdetail->$const;
                                    }
                                //$account_sum+=$pdetail->$const;

                                elseif ($extra_component->source == 4 && $extra_component->operator == 'addition'):

                                    // add the amount to the account sum


                                    $section_sum += $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                //$account_sum+=$pdetail->$const;

                                elseif ($extra_component->source == 4 && $extra_component->operator == 'subtraction'):

                                    // add the amount to the account sum
                                    $section_sum -= $extra_component->percentage > 0 ? ($extra_component->amount * $extra_component->percentage) / 100 : $extra_component->amount;

                                endif;

                            endforeach;
                        endif;

                    endforeach;

                    if ($section_sum > 0) {
                        if ($account->formula > 0) {
                            $section_total[$section->id] += ($section_sum * $account->formula) / 100;
                            $npp += ($section_sum * $account->formula) / 100;
                        } else {
                            $section_total[$section->id] += $section_sum;
                            $npp += $section_sum;
                        }

                    }


                endforeach;
                //end of non payroll provisions
            endif;
            //end of chart of accounts loop
        endforeach;
//Long Service Award Contribution display

        if ($pp->display_lsa_on_nav_export == 1):


            foreach ($sections as $section):
                //non payroll provisions

                $section_users = $section->users;
                //Check if it is a non payroll provision
                if ($account->uses_group == 1):


                    $section_users = $section->users->filter(function ($filtereduser) use ($account) {

                        if ($filtereduser->user_groups->contains('id', $account->group_id)) {
                            return $filtereduser;
                        }

                    });


                endif;


                $users = $section_users->where('expat', 0)->pluck('id');
                $details = $payroll_details->whereIn('user_id', $users);
                $section_sum = 0;
                foreach ($details as $pdetail):
                    // check if the was an entry

                    foreach ($lsas as $award):

                        if ($pdetail->user->months_of_service <= ($award->max_year * 12)):


                            $section_sum += $award->amount / $award->difference / 12;
                            break;

                        endif;


                    endforeach;
                endforeach;

                $npp += $section_sum;
                $section_sum += $section_total[$section->id];

//                $journal=new PayrollJournal();
//                $journal->payroll_id=$payroll->id;
//                $journal->code='PAYROLL-' . date('ym', strtotime($date));
//                $journal->date=date('Y-m-d',strtotime($date));
//                $journal->gl_code='6414111';
//                $journal->description='Non Payroll Provision-' . $section->name;
//
//
//
//                    $journal->project_code= $section->salary_project_code ;
//
//
//
//                        $journal->debit=$section_sum;
//
//                $journal->save();


            endforeach;
        else:
            foreach ($sections as $section):

//                $journal=new PayrollJournal();
//                $journal->payroll_id=$payroll->id;
//                $journal->code='PAYROLL-' . date('ym', strtotime($date));
//                $journal->date=date('Y-m-d',strtotime($date));
//                $journal->gl_code='6414111';
//                $journal->description='Non Payroll Provision-' . $section->name;
//
//
//
//                $journal->project_code= $section->salary_project_code ;
//
//
//
//                $journal->debit=$section_total[$section->id];
//
//                $journal->save();


            endforeach;

        endif;

//        $journal=new PayrollJournal();
//        $journal->payroll_id=$payroll->id;
//        $journal->code='PAYROLL-' . date('ym', strtotime($date));
//        $journal->date=date('Y-m-d',strtotime($date));
//        $journal->gl_code='6414111';
//        $journal->description='Non Payroll Provision-' . $section->name;
//        $journal->credit=$npp;
//        $journal->save();


    }
}
