import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

// Layouts

import { LoginComponent } from './layouts/login.component';
import { PanelComponent } from './layouts/panel.component';

//------------------------------------------------------------------------------
//  Pages
//------------------------------------------------------------------------------

import { HomePageComponent } from './pages/home/home.component';
import { DebtsPageComponent } from './pages/debts/debts.component';
import { UsersPageComponent } from './pages/users/users.component';

export const routes: Routes = [
  {
    path: '',
    redirectTo: 'login',
    pathMatch: 'full',
  },
  {
    path: 'login',
    component: LoginComponent,
  },
  {
    path: '',
    component: PanelComponent,
    data: {
      title: 'Panel'
    },
    children: [
      {
        path: 'home',
        component: HomePageComponent,
        data: {
          title: 'Inicio'
        }
      },
      {
        path: 'debts',
        component: DebtsPageComponent,
        data: {
          title: 'Deudas'
        }
      },
      {
        path: 'users',
        component: UsersPageComponent,
        data: {
          title: 'Usuarios'
        }
      }
    ]
  }
];

@NgModule({
  imports: [ RouterModule.forRoot(routes) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule {}
