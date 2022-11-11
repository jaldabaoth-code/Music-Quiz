<h1>Music Quiz (Hackathon 3, WCS Web PHP)</h1>

### Create a music themed website by using Symfony


---

### index

1. [Prerequisites](#Prerequisites)
2. [Users](#Users)
3. [Installation](#Steps)
4. [Authors](#Authors)

### Prerequisites

* [PHP 7.4.*](https://www.php.net/releases/7_4_0.php) (check by running php -v in your console)
* [Composer 2.*](https://getcomposer.org/) (check by running composer --version in your console)
* [node 14.*](https://nodejs.org/en/) (check by running node -v in your console)
* [Yarn 1.*](https://yarnpkg.com/) (check by running yarn -v in your console)
* [Git 2.*](https://git-scm.com/) (check by running git --version in your console)

### Steps

If you meet the prerequisites, you can proceed to the installation of the project 

1. Clone the repo from GitHub : `git@github.com:jaldabaoth-code/Music-Quiz.git`
2. Enter the directory : `cd Music-Quiz`
3. Open with your code editor
4. Run `composer install` to install PHP dependencies
5. Run `yarn install` to install JS dependencies
6. Copy the `.env` file and fill Database information
    - MAILER_DSN=smtp://xxx<br/>
        * "Retrieve and copy API_MUSIC_STORY_CONSUMER_KEY and API_MUSIC_STORY_CONSUMER_SECRET from : <a href="https://user.music-story.com/fr/contrat">Music Story.</a></b>
7. Run `yarn encore dev` to build assets
8. Run `symfony server:start` to launch symfony server
9. Go to <b>localhost:8000</b> with your favorite browser

### Authors

* [Gersey Stelmach](https://github.com/gerseystelmach)
* [Zurabi Grialat](https://github.com/jaldabaoth-code)

---

## The Links

<a href="https://github.com/gerseystelmach/MusicQuiz">Link to the repository of project where we worked during <b>WCS Web Hackathon 3</b></a>
