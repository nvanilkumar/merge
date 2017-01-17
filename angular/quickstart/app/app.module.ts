import { NgModule }       from '@angular/core';
import { BrowserModule, }  from '@angular/platform-browser';
import { FormsModule,ReactiveFormsModule }    from '@angular/forms';
import { HttpModule, JsonpModule } from '@angular/http';

import { AppComponent }         from './app.component';
import { CatListComponent }   from './cats/cat-list.component';
import { CatDetailsComponent }  from './cats/cat-details.component';
import { DogListComponent }      from './dogs/dog-list.component';
import { DogDetailsComponent }  from './dogs/dog-details.component';
import { UserComponent }  from './user/user.component';
import { PetService }          from './pet.service';
import { Pet }          from './pet';
import { routing } from './app.routes';


 

@NgModule({
  imports: [
    BrowserModule,
    ReactiveFormsModule,
    FormsModule,
     HttpModule,
    JsonpModule,
    routing
  ],
  declarations: [
    AppComponent,
    CatListComponent,
    CatDetailsComponent,
    DogListComponent,
    DogDetailsComponent,
    UserComponent
  ],
  providers: [
    PetService
  ],
  bootstrap: [ AppComponent ]
})
export class AppModule {
}

