# Open Publisher
💡 Open publishing process based in crowdfunding.  

## Context
Given a simplified publishing process:  
Original -> proofreading -> [opt] translating -> layout design -> printing -> distributing  

This project aims to make a platform that offers:  
- a marketplace to find services  
- a pipeline for authors to manage the process.  

# How to install  

1. Clone repository  
```sh
git clone git@github.com:Kaylen7/Sprint5-publishers.git publishers
cd publishers
```

2. Install PHP dependencies  
```sh
composer install
```

3. Generate laravel encryption key  
```sh
php artisan key:generate
```

4. Run migrations and seeder (encouraged for testing purposes)  
```sh
php artisan migrate --seed
```
The seeder will create some projects and services, as well as a test user with creds:  
**email**: test@example.com  
**password**: password  

5. Copy `.env.example` to `.env`. Update `APP_URL` if necessary.  
```sh
cp .env.example .env
```

6. Create passport client.  
```sh
php artisan passport:client --password
```
Leave default name, press enter and make sure you copy client id and client secret to `.env` fields: `PASSWORD_CLIENT_ID` and `PASSWORD_CLIENT_SECRET`.   

Now the project is all set, you should be able to serve it with `php artisan serve` and check documentation `api/documentation`.   

>[!WARNING]
> `api/documentation` works with project database. There's an ongoing branch to change this so that it uses `:memory:` database instead.   

# Tests
This project has been developed with TDD, so there's a test suite in `tests/`. It uses:  
- `.env.testing` configuration  
- `:memory:` database  
- `TestSeeder` class  

It's encouraged that you run it with `--parallel` flag for time optimization (1.76s vs 7.46s).  
```sh
php artisan test --parallel
```

# v1
Early version of base project. Features CRUDS for user, project and services, with oauth authentication and regular/admin authorization.   

- Admin is created through migration rules. All users created from `/register` endpoint are regular users.  
- User can only have a service for each type. Right now there's only `proofreading` and `translating` types.  

### Endpoints and permissions
| **endpoint**                                      | **admin** | **user** | **description**                                  |
| ------------------------------------------------- | --------- | -------- | ------------------------------------------------ |
| `POST api/register`                               | [x]       | [x]      | register user                                    |
| `POST api/login`                                  | [x]       | [x]      | login                                            |
| `POST api/logout`                                 | [x]       | [x]      | logout                                           |
|                                                   |           |          |                                                  |
| `GET api/users/{id}`                              | [x]       | [x]      | show specific user                               |
| `GET api/users/`                                  | [x]       | [x]      | show users                                       |
| `GET api/users/{id}/projects`                     | [x]       | [x]      | show projects done by user                       |
| `PUT api/users/{id}`                              | [x] all   | [x]  own | edit user                                        |
| `DELETE api/users/{id}`                           | [x] all   | [x]  own | remove user                                      |
|                                                   |           |          |                                                  |
| `GET api/projects`                                | [x]       | [x]      | show all projects                                |
| `GET api/projects/{id}`                           | [x]       | [x]      | show specific project                            |
| `POST api/projects`                               | [x]       | [x]      | create project                                   |
| `PUT api/projects/{id}`                           | [x] all   | [x]  own | edit project                                     |
| `DELETE api/projects/{id}`                        | [x] all   | [x]  own | remove project                                   |
|                                                   |           |          |                                                  |
| `GET api/services`                                | [x]       | [x]      | show services.                                   |
| `GET api/services/{id}`                           | [x]       | [x]      | show service                                     |
| `POST api/services`                               | [x]       | [x]      | create service                                   |
| `PUT api/services/{id}`                           | [x] all   | [x]  own | edit service                                     |
| `DELETE api/services/{id}`                        | [x] all   | [x]      | remove service                                   |
|                                                   |           |          |                                                  |