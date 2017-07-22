# FocaDeudas

\#TODO WTF is this?

## Back-End

Located at `/api` stores all the structure and end-points required in order to interact with the system.

\#TODO: controllers types

\#TODO: controllers structure.

### Instalation

In order to run the application there's a couple os things to do:

* First of all, a `MySQL` with the schema found at `/api/core/database/schema.sql`.
* Once the DB all whats left is to configure the environment located at `/api/core/environments/dev.json` and `prod.json` for your production environment _note that it will determine if is prod or dev based on whether is https or not_.

## Front-End

Located at `/panel` is build as Angular 4 application taking [Angular 4 Root](https://github.com/mrholek/Root-Bootstrap-4-Admin-Template-with-AngularJS-Angular-2-support) as start point.

## Instalation

* Download and install [NodeJS](https://nodejs.org/en/)
* Install the Angular CLI `npm install -g @angular/cli`
* Install all the dependencies `npm install`
* And launch the angular cli `ng serve`

And you are ready to go, a development server will be at `localhost:4200`

\#TODO: may things