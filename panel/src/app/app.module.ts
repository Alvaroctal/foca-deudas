import { NgModule, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { LocationStrategy, HashLocationStrategy, CommonModule} from '@angular/common';

import { AppComponent } from './app.component';
import { BsDropdownModule } from 'ngx-bootstrap/dropdown';
import { ProgressbarModule } from 'ngx-bootstrap/progressbar';
import { NAV_DROPDOWN_DIRECTIVES } from './shared/nav-dropdown.directive';

import { SIDEBAR_TOGGLE_DIRECTIVES } from './shared/sidebar.directive';
import { AsideToggleDirective } from './shared/aside.directive';
import { BreadcrumbsComponent } from './shared/breadcrumb.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

import { ClipboardModule } from 'ngx-clipboard';
import { NouisliderModule } from 'ng2-nouislider';

//------------------------------------------------------------------------------
//  Services
//------------------------------------------------------------------------------

import { ApiService } from './services/api.service';
import { NotificationService } from './services/notification.service';
import { LoggedUserService } from "./services/logged.user.service";

//------------------------------------------------------------------------------
//  Layouts
//------------------------------------------------------------------------------

import { LoginComponent } from './layouts/login.component';
import { PanelComponent } from './layouts/panel.component';

//------------------------------------------------------------------------------
//  Pages
//------------------------------------------------------------------------------

import { HomePageComponent } from './pages/home/home.component';
import { DebtsPageComponent } from './pages/debts/debts.component';
import { UsersPageComponent } from './pages/users/users.component';

//------------------------------------------------------------------------------
//  Modals
//------------------------------------------------------------------------------

import { PaymentModalComponent } from './pages/home/modals/payment.modal.component';
import { DebtModalComponent } from './pages/debts/modals/debt.modal.component';
import { UserModalComponent } from './pages/users/modals/user.modal.component';

//------------------------------------------------------------------------------
//  Modules
//------------------------------------------------------------------------------

import { HttpModule } from '@angular/http';
import { AppRoutingModule } from './app.routing';
import { FormsModule } from '@angular/forms';
import { CalendarModule } from 'angular-calendar';
import { SelectModule } from 'ng-select';
import { ModalModule } from 'ngx-bootstrap/modal';
import { ToasterModule } from 'angular2-toaster/angular2-toaster';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';


@NgModule({
  imports: [
    BrowserModule,
    AppRoutingModule,
    BsDropdownModule.forRoot(),
    ProgressbarModule.forRoot(),
    HttpModule,
    FormsModule,
    ModalModule,
    SelectModule,
    ToasterModule,
    BrowserAnimationsModule,
    CalendarModule.forRoot(),
    CommonModule,
    NgxDatatableModule,
    ClipboardModule,
    NouisliderModule
  ],
  declarations: [
    AppComponent,
    LoginComponent,
    PanelComponent,
    HomePageComponent,
    DebtsPageComponent,
    UsersPageComponent,
    DebtModalComponent,
    UserModalComponent,
    PaymentModalComponent,
    NAV_DROPDOWN_DIRECTIVES,
    BreadcrumbsComponent,
    SIDEBAR_TOGGLE_DIRECTIVES,
    AsideToggleDirective
  ],
  providers: [
    ApiService,
    NotificationService,
    LoggedUserService,
    {
      provide: LocationStrategy,
      useClass: HashLocationStrategy
    }
  ],
  bootstrap: [ AppComponent ],
  schemas: [ CUSTOM_ELEMENTS_SCHEMA ],
  entryComponents: [ DebtModalComponent, UserModalComponent, PaymentModalComponent ]
})
export class AppModule { }
