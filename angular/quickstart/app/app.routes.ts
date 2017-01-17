// Imports
// Deprecated import
// import { provideRouter, RouterConfig } from '@angular/router';
import { ModuleWithProviders }  from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { dogRoutes }    from './dogs/dog.routes';
import { catRoutes }    from './cats/cat.routes';
import { userRoutes }    from './user/user.routes';

// Route Configuration
export const routes: Routes = [
  {
    path: '',
    redirectTo: '/dogs',
    pathMatch: 'full'
  },
  ...catRoutes,
  ...dogRoutes,
  ...userRoutes
];

 

export const routing: ModuleWithProviders = RouterModule.forRoot(routes);
