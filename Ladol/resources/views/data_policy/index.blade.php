
<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="bootstrap material admin template">
    <meta name="author" content="">

    <title>Data Policy | HCMatrix</title>

    <link rel="apple-touch-icon" href="{{ asset('assets/images/apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
    <!-- Stylesheets -->
    <script src="https://cdn.tailwindcss.com"></script>
    
   
</head>
<body >
<div class="grid grid-cols-1 lg:grid-cols-2 h-screen">
     
      <div class="bg-white hidden lg:flex shadow-lg">
    
        <div
          class="fixed lg:w-2/4 top-0  bg-cover bg-no-repeat bg-gradient-to-t from-green-700 to-green-100 left-0 z-20 h-screen shadow-lg flex flex-col gap-4 items-center justify-end pt-12 pb-10"
        >
        
            <div>
            <img  src="{{ asset('hcm.png') }}" class='h-52 object-contain hidden lg:block'>
            </div>
          
          <div class=" text-white mt-4">
            <p class="font-bold text-lg text-center mb-0">HcMatrix</p>

            <span class="font-light text-xs  text-sky-300 italic">
            RECRUIT | RETAIN | REWARD
            </span>
          </div>
        </div>
      </div>
      <div class="flex flex-col gap-4 items-center pt-8">
        <div class="flex flex-col items-center">
          <img  src="{{ asset('hcm.png') }}" class="lg:hidden">

          <h4 class="text-2xl uppercase font-bold text-slate-700 underline"> Data Privacy Policy</h4>
          
        </div>
        <hr/>
        <div class="w-4/5 text-left flex flex-col gap-4 pb-8 text-sm text-slate-600">
            <p class="">
            We respect the privacy rights of all individuals, and we are committed to handling personal data responsibly and in accordance with applicable laws. This privacy notice, together with all relevant documents and other notices provided at the time of data collection, explain what personal data LADOL collects about you, how we use this personal data, and your rights to this personal data. 
            </p>
            <p>
            Please note that this privacy notice applies to the handling of your personal data as an employee, former employee, candidate, guest, or as external staff. (“External staff” are workers who are not employed by LADOL and who have access to LADOL's facilities and/or LADOL's corporate network. This could include agency temporary workers, outsourced staff, contractors, and business guests.) LADOL has additional governance and privacy requirements concerning the collection and use of personal data. 
            </p>
            <p>
            This notice is not intended and shall not be read to create any express or implied promise or contract for employment, for any benefit, or for specific treatment in specific situations. Nothing in this notice should be construed to interfere with LADOL's ability to process employee data for purposes of complying with our legal obligations, or for investigating alleged misconduct or violations of company policy or law, subject to compliance with local legal requirements.  
            </p>
            <p>
            LADOL's processing of personal data is in all cases subject to the requirements of applicable local law, internal policy, and where applicable or appropriate, any consultation requirements with worker representatives. To the extent this notice conflicts with local law in your jurisdictions, local law controls.  
            </p>
            <form class="flex gap-4 flex-col" action="{{route('data_policy_acceptance')}}" method="POST">
                <div class="block">
                    <div class="mt-2" >
                      @csrf
                        <label class="inline-flex items-baseline lg:items-center">
                        <input type="checkbox" class="w-4 h-4"  name = "accepted"/>
                        <span class="ml-2">Do you agree to the terms and conditions of this Data Privacy Policy? </span>
                        </label>
                    </div>
                </div>
                <button class="bg-green-600 text-white shadow-lg px-4 py-2 rounded-sm uppercase">Submit</button>
            </form>
        </div>

      </div>
    </div>


<!-- Page -->
   

   
<!-- End Page -->





</body>

</html>