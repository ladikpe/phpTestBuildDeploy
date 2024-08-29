// hmo hospital

export interface THMOHospital {
  id: number;
  hmo: number;
  hospital: string;
  band: string;
  category: string;
  coverage?: any;
  address: string;
  contact: string;
  created_at: string;
  updated_at: string;
  user_count?: number;
  hmos?: Hmo2[];
}

interface Hmo2 {
  id: number;
  hmo_id: number;
  hmohospitals_id: number;
  created_at: string;
  updated_at: string;
  hmo: Hmo;
}

interface Hmo {
  id: number;
  hmo: string;
  description: string;
  created_at: string;
  updated_at: string;
}

// hmo
export interface THMO {
  id: number;
  hmo: string;

  description?: string;
  created_at: string;
  updated_at: string;
  hmohospitals?: {
    id: number;
    hmo: number;
    hospital: string;
    band: string;
    category: string;
    coverage?: any;
    address: string;
    contact: string;
    created_at: string;
    updated_at: string;
    pivot: Pivot;
  }[];
}

interface Pivot {
  hmo_id: number;
  hmohospitals_id: number;
  created_at: string;
  updated_at: string;
}
