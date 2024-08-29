export interface TPerformanceDiscussion {
  id: number;
  evaluation_id: number;
  title: string;
  discussion: string;
  created_at: string;
  updated_at: string;
  employee_submitted?: any;
  line_manager_approved?: any;
  line_manager_approval_date?: any;
  employee_submission_date?: any;
  rejection_reason?: any;
  participant_id: number;
  discussion_details: TDiscussiondetail[];
}

export interface TDiscussiondetail {
  id: number;
  performance_discussion_id: number;
  evaluation_detail_id: number;
  evaluation_detail: EvaluationDetail;
  action_update?: any;
  challenges?: any;
  comment?: any;
  created_at: string;
  updated_at: string;
}

interface EvaluationDetail {
  id: number;
  bsc_evaluation_id: number;
  metric_id: number;
  focus: string;
  key_deliverable: string;
  measure_of_success: string;
  means_of_verification: string;
  is_penalty: number;
  objective: string;
  accept_reject?: any;
  evaluator_id?: any;
  target?: any;
  achievement?: any;
  self_assessment: string;
  weight: string;
  manager_assessment: string;
  manager_of_manager_assessment?: any;
  justification_of_rating?: any;
  head_of_hr_comment?: any;
  head_of_strategy_comment?: any;
  created_at: string;
  updated_at: string;
  score: string;
  employee_comment: string;
  modified_date: string;
}
