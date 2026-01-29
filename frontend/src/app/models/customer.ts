export interface Customer {
  id?: number; // auto-increment, optionnel à la création
  nombre: string;
  surname1: string;
  surname2?: string; // nullable
  age: number;
  email: string;
  phoneNumber: string;
}

// Alias pratique pour un tableau de Customer
export type CustomerList = Customer[];
