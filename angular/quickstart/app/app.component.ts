import { Component } from '@angular/core';
// Import router directives
// Deprecated
// import { ROUTER_DIRECTIVES } from '@angular/router';


@Component({
  selector: 'my-app',
  template: `
      <div class="demo-layout-transparent mdl-layout mdl-js-layout">
      <header class="mdl-layout__header mdl-layout__header--transparent">
        <div class="mdl-layout__header-row">
          <!-- Title -->
          <span class="mdl-layout-title">Scotch Pets</span>
          <!-- Add spacer, to align navigation to the right -->
          <div class="mdl-layout-spacer"></div>
          <!-- Navigation with router directives-->
          <nav class="mdl-navigation">
            <a class="mdl-navigation__link" [routerLink]="['/']">Home</a>
            <a class="mdl-navigation__link" [routerLink]="['/cats']">Cats</a>
            <a class="mdl-navigation__link" [routerLink]="['/dogs']">Dogs</a>
            <a class="mdl-navigation__link" [routerLink]="['/adduser']">Add User</a>
          </nav>
        </div>
      </header>
      <main class="mdl-layout__content">
        <h1 class="header-text">We care about pets...</h1>
      </main>
    </div>
    <!-- Router Outlet -->
    <div class="container">
      <router-outlet></router-outlet>
    </div>
  `,
  // Deprecated
  // Tell component to use router directives
  // directives: [ROUTER_DIRECTIVES]
})

// App Component class
export class AppComponent{}
