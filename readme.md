# Fictícia Login System (Test for job)
## Get Started

Create database

```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
);
```

Configure connection in .env file

```txt
DBHOST=localhost
DBNAME=ficticia
DBUSER=root
DBPASS=
```

Copy the files to your hosting

## Operation

In first access the system check if has users registered, if not the system redirect you to register the first user.

![first access login page](https://github.com/jhonesdev/ficticia-login-system/blob/main/assets/images/doc_1.png?raw=true)

![first access users page](https://github.com/jhonesdev/ficticia-login-system/blob/main/assets/images/doc_2.png?raw=true)

After create user your is redirect to login page and dont have access to users page if not logged.

![first access login page](https://github.com/jhonesdev/ficticia-login-system/blob/main/assets/images/doc_3.png?raw=true)

![first access login page](https://github.com/jhonesdev/ficticia-login-system/blob/main/assets/images/doc_4.png?raw=true)


## License

MIT

**Free Software, Hell Yeah!**