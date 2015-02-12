# How to contribute

Contributing to AsgardCMS is fairly straitforward. The easiest way is listed below. 

Please note AsgardCMS follows **[PSR-1](http://www.php-fig.org/psr/psr-1/)** and **[PSR-2](http://www.php-fig.org/psr/psr-2/)**. Please make sure your code follows those standards.

You can use a great tool : **[PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)** to make sure everything is following the correct coding style.

Every module has a `.php_cs` config file to run **php-cs-fixer** with:

```
$ cd Modules/{ModuleName}
$ php-cs-fixer fix . --config-file=".php_cs" --verbose
```

**Please run php-cs-fixer before sending a pull request**

## Getting setup
### Modules 

- Have an installation of [AsgardCMS/Platform](https://github.com/AsgardCms/Platform) 
- Remove `.git/` directory
- Remove all modules from `composer.json` file
- `cd` into the `Modules/` folder and clone each Module individually
- Install AsgardCMS as usual `php artisan asgard:install`

### Themes

This is the same as modules except `cd`-ing into the `Themes/` folder and cloning desired themes in there.



## Making changes

Once you have your copy of AsgardCMS installed and configured for contributing purposes, you're ready to make changes.

AsgardCMS follows a workflow similar to **[Git Flow branching model](https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow/)**.

This means:

- For a new feature: 
	- Create a branch `feature/your-new-feature-name`
	- Add you changes
	- Make sure the test suite for the module still passes
	- [Squash commits](https://ariejan.net/2011/07/05/git-squash-your-latests-commits-into-one/) if necessary to create a nice history
	- Send a pull request to the `develop` branch of the module/theme your modifying
- For a hotfix:
	- Create a branch `hotfix/your-hotfix-name`
	- Add a failing test that reproduces the found bug
	- Add you changes by making the test pass
	- [Squash commits](https://ariejan.net/2011/07/05/git-squash-your-latests-commits-into-one/) if necessary to create 	a nice history
	- Send a pull request to the `develop` branch of the module/theme your modifying


# Additional Resources

* [General GitHub documentation](http://help.github.com/)
* [GitHub pull request documentation](http://help.github.com/send-pull-requests/)
* `#asgardcms`IRC channel on freenode.org

