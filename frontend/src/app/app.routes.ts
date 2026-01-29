import { Routes } from '@angular/router';
import { Customers } from './customers/customers';
import { EditCustomer } from './customers/edit-customer/edit-customer';
import { NewCustomer } from './customers/new-customer/new-customer';

export const routes: Routes = [
  {
    path: '',
    redirectTo: 'customers',
    pathMatch: 'full',
  },
  {
    path: 'customers',
    component: Customers,
    title: 'Clients',
  },
  {
    path: 'customer/edit',
    component: EditCustomer,
    title: 'Editer client',
  },
  {
    path: 'customer/new',
    component: NewCustomer,
    title: 'Nouveau client',
  },
];
