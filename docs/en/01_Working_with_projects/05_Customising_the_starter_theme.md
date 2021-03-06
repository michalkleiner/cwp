title: Customising the CWP Starter theme
summary: Development information for working with themes

# Customising the CWP Starter theme

## Introduction

CWP provides a *CWP Starter* theme for you to work with. This has been developed both as a base for more complex themes
to be built on top of and as a reference example for meeting Government standards and accessibility guidelines.

For more information on how a SilverStripe theme is constructed, see the [Developing
Themes](https://docs.silverstripe.org/en/4/developer_guides/templates/themes/) page in the SilverStripe documentation.

When customising the theme, you can choose to work either with the powerful SASS framework (as explained below), or to
use the CSS stylesheets directly. In the latter case we recommend you to remove the `.scss` files to make it clear they
are not used.

We recommend you follow the CWP Starter theme as a guideline and use the provided configuration to build and
recompile your frontend dependencies.

<div class="alert alert-info" markdown='1'>
Note: The Starter theme is the default CWP theme as of the CWP 1.6 recipe. If you are looking for the old "default"
CWP theme, [please see here](https://www.cwp.govt.nz/developer-docs/en/1.5/working_with_projects/customising_the_default_theme/).
</div>

## Getting Started

The CWP Starter theme that comes with the basic site is cloned from the
[CWP Starter theme in GitHub](https://github.com/silverstripe/cwp-starter-theme), so you are not able to make changes
to it directly. There are two recommended ways of creating your own theme:

### Forking the theme

This will give you a copy of the CWP Starter theme repository that you can edit and can share with others.

1. Browse to the [CWP Starter theme](https://github.com/silverstripe/cwp-starter-theme) in GitHub.
2. Click on the *Fork* button in the toolbar. This will make a copy of the theme in your GitHub profile.
3. Note: if you need this repository to be private, you must
  [duplicate the repository](https://help.github.com/articles/duplicating-a-repository) instead, since GitHub doesn't
  support making public forks private.
4. In your theme's `composer.json` file and change the `"name"` parameter to `"my-agency/<theme-name-here>"`.
5. In your project's `composer.json` replace `"cwp/starter-theme"` with with the name you added in the previous step.
6. In the same `composer.json` file replace the `"https://github.com/silverstripe/cwp-starter-theme"` with your
private repository address to the `repositories` array - see below for sample. You may need to add this manually.
7. Edit the `app/_config/config.yml` file and change the theme from `starter` to your theme's name.
8. Run `composer update`.

#### Example composer.json files

Your theme's composer.json file should look something like this:

```json
{
    "name": "my-agency/my-new-theme",
    "description": "My New Theme",
    "type": "silverstripe-theme",
    "require": {
        "silverstripe/framework": "^4.0"
    },
    "extra": {
        "branch-alias": {
            "dev-master": "2.x-dev"
        }
    }
}
```

Your project's composer.json file should look something like this:

```json
{
    "name": "my-agency/my-project",
    "description": "My CWP project",
    "require": {
        "cwp/cwp-recipe-cms": "~2.3.0@stable",
        "my-agency/my-new-theme": "2.x-dev"
    },
    "require-dev": {
        "cwp/cwp-recipe-basic-dev": "1.0.1"
    },
    "config": {
        "process-timeout": 900
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/my-agency/my-new-theme.git"
        }
    ],
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

That will have you set up with your own copy of the theme in the folder `/themes/my-new-theme`. You can also share your
theme with others by adding them as team members to your project, or even making the repository public.

Note that you will need to add `"private": "true"` to the `repositories` array if you are using a private repository
hosted on GitLab. This is not required for GitHub repositories. See [Working with modules](working_with_modules) for
more information on this feature.

### Committing a theme to your project repository

This is a more straightforward process but not as flexible - you won't be able to share the theme with anyone that you
also don't want to have access to your site's code as well, and will make it more difficult to update when the CWP
Starter theme is updated.

 1. Edit your project's `.gitignore` file and remove the `themes/` line.
 2. Remove the `/themes/starter/.git` directory and the `/themes/starter/.gitignore` file.
 3. Rename the "starter" folder to your projects name (it should be all lower case and preferably one word).
    * If you're also using the [Wātea theme](https://github.com/silverstripe/cwp-watea-theme) you may also wish to
    change the theme name from "watea" to something else as well. If you do this, ensure you update your `theme.yml`.
 4. Add the `.gitignore` file and the `themes` folder to your git project, commit it and push it back to the upstream
    repository.
 5. Remove the "cwp/starter-theme" line from the `"require"` list in `composer.json`. This will prevent Composer from
    re-installing the *starter* theme in your project.
 6. Edit `app/_config/theme.yml` and add your theme name to the list in `SilverStripe\View\SSViewer.themes`.
    If you're using the public webroot feature (enabled by default from CWP 2.0 onwards) ensure you add your custom
    theme _after_ `'$public'`.

<div class="alert alert-info" markdown='1'>
Don't forget to `flush` by visiting `http://localhost/your-project/?flush=1` to get the new theme running!
</div>

Commit all changed files to your project repository so other collaborators can see it. This will include
`composer.lock` file that "freezes" the current version of the modules to the ones you have currently included.

```
git add --all
git commit -m "Add custom theme."
git push origin master
```

Now when you go into GitHub/GitLab, you'll see a commit from yourself "Add custom theme."

## Bootstrap

The CWP Starter theme is built on top of the [Bootstrap 4](http://getbootstrap.com/) front-end framework.

Bootstrap is a free collection of tools for creating websites and web applications. It contains HTML and
CSS-based design templates for typography, forms, buttons, charts, navigation and other interface components, as well
as optional JavaScript extensions.

From Bootstrap's [Getting Started page](http://getbootstrap.com/getting-started/) you can find links about the basics
of the system and the full documentation.

The CWP Starter theme's `package.json` dependencies include the Bootstrap stylesheets, and the compiled CSS output also
includes the final output.

### Browser support

The Starter and Wātea themes use Bootstrap 4, which
[supports the following browsers](https://getbootstrap.com/docs/4.3/getting-started/browsers-devices/#supported-browsers):

* Internet Explorer 10 or above
* Edge 12 or above
* Firefox 38 or above
* Chrome 45 or above
* Safari 9 or above

### Bootstrap in the *CWP Starter* theme

If you just want to dive in without reading the manual, the most important thing to understand is the [grid
system](https://getbootstrap.com/docs/4.3/layout/grid/). In a nutshell, `.row` is a full-width
container that can contain 12 columns. Elements define the number of columns they take up by using `.col-*-*` classes,
where an asterisk is replaced with a [viewport reference](https://getbootstrap.com/docs/3.3/css/#grid-options), and
the other is replaced with the number of columns between 1 and 12. Take a look at the simple example in
`/themes/starter/templates/Layout/Page.ss` and see how the sidenav and content sit beside each other.

The basic page layout uses a `.col-md-7` on the left for the content and a `.col-md-3` for the main content area.
The `.col-md-offset-1` class creates an indent for the content on the left side of the page.

## Sass and Javascript

The CWP Starter theme is build using Bootstrap's Sass source code to compile a customised version of Bootstrap, where
colours, margins, padding etc are modified and recompiled within the context of the rest of Bootstrap's source code.

We've added very little CSS and JS to the core Bootstrap 4 feature set, but that which we have added is neatly packaged
in the theme folder. The files are built using a build chain abstraction, called
[Laravel Mix](https://laravel.com/docs/5.4/mix). It uses [Webpack](https://webpack.github.io) to convert, combine,
minify and improve the quality of CSS and JS files.

Sass (Syntactically Awesome Stylesheets) is a preprocessed stylesheet language, compiling to CSS. Sass adds nested
rules, variables, mixins, selector inheritance, functions and other such useful things to CSS3. SCSS is a syntax of
Sass based on CSS syntax.

For more information on how to use SCSS, and full API documentation see: http://sass-lang.com/.

If you take a look at the `webpack.mix.js` file in the `themes/starter` folder, you will see some instructions for how
the Sass and Javascript files are loaded, processed, combined and/or minified then pushed to a "dist" file.

An example folder structure might look like this:

```
themes/starter/
    dist/
	    css/
		    main.css
		js/
		    main.js
	src/
	    scss/
		    components/
			    my-component.scss
		    main.scss
		js/
			components/
				my-component.js
			main.js
```

In the above example, the "src" files are all loaded and processed (although there is only one in the example) and a
combined and rendered output file is added to the respective "dist" folder. When referencing frontend assets from
templates and `Requirements` calls you should use the "dist" files.

### Installing Webpack and Laravel Mix

You'll need to have recent versions of [Node.js](https://nodejs.org/en/) and [Yarn](https://yarnpkg.com/en/) installed
for this build chain to work. We recommend Node `v10.x` or later and the latest version of Yarn. You can check which
versions you have by running `node -v` and `yarn -v` respectively.

Once you have Node and Yarn installed, you can install the required package dependencies for the theme:

```
cd themes/starter
yarn install
```

You should now have the required tools installed. You can see what the package requires by inspecting `package.json`
in the theme directory - an example `package.json` might contain a section like this:

```
"dependencies": {
  "bootstrap": "^4.3",
  "expose-loader": "^0.7",
  "font-awesome": "^4.7.0",
  "jquery": "^3.1.1",
  "jquery-highlight": "^3.3.0",
  "laravel-mix": "^4.0"
},
"devDependencies": {
  "babel-eslint": "^7.1.1",
  "eslint": "^3.13.1",
  "eslint-config-airbnb": "^14.0.0",
  "eslint-plugin-import": "^2.2.0",
  "eslint-plugin-jsx-a11y": "^3.0.2",
  "eslint-plugin-react": "^6.9.0",
  "sass-lint": "^1.10.2",
  "webpack": "^4"
}
```

To validate that they have been installed correctly, run a test build:

```
yarn build
```

You should see "OK" in the console if everything compiled correctly.

Building the bundled files can be slow, especially as you add much more code. Instead, consider running the file
watcher (in a background tab) so that the bundles can be partially rebuilt as needed: `yarn watch`.

If you have any questions about how to customise Laravel Mix, take a look at the
[official documentation](https://github.com/JeffreyWay/laravel-mix/tree/master/docs).

### Adding JS and CSS files

If you want to add new Sass (CSS styles) or Javascript functionality you should create a new "component" in the
"src/scss|js/components" folder of the theme and add a reference to it from the `main.scss` or `main.js` file in
the folder above it, for example:

**File:** themes/starter/src/scss/components/my-component.scss

```scss
.my-parent-selector {
  .my-child-selector {
    // Make the text colour green!
    color: green;
  }
}
```

And add the following to src/scss/main.scss:

```scss
@import "./components/my-component";
```

You can follow the same process for Javascript files - take a look at the existing components for examples.

<div class="alert alert-info" markdown='1'>
It's generally encouraged to use Sass variables wherever possible. You can find a list of all predefined variables
and values in the src/scss/variables.scss file. This file is based on the default Bootstrap 4 variable sheet, with
some changes made and some new variables added. Changes are annotated with the original values in comments.
</div>

Once you have added the new components, changed styles etc, you should run `yarn build` to compile and produce
the "dist" files. On successful completion you can add and commit your updates files.

**Note:** When working in a team environment it will not be uncommon to have merge conflicts in dist files. If
this happens and conflicts are confined to dist files, simply run `yarn build` again and add the rebuilt files.

### Building for production

When you're about to release a new production-ready version of your theme you should run the "package" process
instead. This will perform a build, but will also minify the output to decrease the overall file size and help
keep your website as speedy as possible:

```
yarn package
```

## Modifying template files

The CWP Starter and Wātea themes are built with the same
[template syntax SilverStripe developers are used to](https://docs.silverstripe.org/en/4/developer_guides/templates).
Here's what has been changed from the previous "default" theme:

* Use [Bootstrap 4](http://getbootstrap.com) HTML and CSS, instead of Bootstrap 2
* Use a new, simplified build chain ([Laravel Mix](https://laravel.com/docs/5.4/mix))
* Perform a full accessibility review (with external assessors)
* Add translatable strings for all hard-coded template text

Given these changes, all you need to do to modify the theme is dive into the template files in the theme located
in the `templates` folder of the theme.

When you make changes to a template, be sure to flush the site cache (by appending `flush=1` to the query string.

## Working with standards

In his book [Clean Code](https://www.amazon.com/dp/0132350882), Robert Martin talks about the importance of reading
code vs. writing code. That what we write needs to be entirely focused on being easy to read and understand.
That writing something succinctly is a waste of time if the effort makes understanding it harder.

That's the main reason that the CWP Starter and Wātea themes have opted to use
[AirBnB code styles for Javascript](https://github.com/airbnb/javascript) and
[AirBnB CSS/Sass styleguide](https://github.com/airbnb/css) for Sass and CSS (with a minor adjustment to follow
Bootstrap's class naming convention of single dashes rather than BEM). You don't have to use these, in your project,
but if you do all of your code will resemble the style used in the Sass and JS theme files.

## Javascript linting

The [AirBnB JS code style guide](https://github.com/airbnb/javascript) is applied, using
[ESLint](https://github.com/eslint/eslint).

> This assumes you're going for AirBnB Javascript code styles. You can of course configure ESLint to your preferred standard.

It would also help if you configured ESLint to allow global variables (like `window` and `document`):

**File:** `.eslintrc`
```json
{
    "extends": "airbnb",
    "rules": {
        "func-names": "off"
    },
    "env": {
        "browser": true,
        "node": true,
        "jasmine": true
    }
}
```

You can run Javascript linting from the command line:

```
yarn lint-js
```

You can also install an ESLint plugin for your IDE.

## SASS linting

This theme comes with configuration for the [sass-lint](https://github.com/sasstools/sass-lint) npm module. You can
run linting over the SASS files in this theme with the following command:

```
yarn lint-sass
```

The style rules are based on the [AirBnB CSS/Sass style guide](https://github.com/airbnb/css). The exception is the
BEM class naming rules, which has been substituted for Bootstrap-style class naming rules for compatibility with its
framework. For example a BEM class name such as `.ListingCard__content` would be better suited here written
a `.listingcard-content`.

If you want to change the pre-configured rules for the linter, you can adjust the `.sass-lint.yml` file in the
theme's root directory.
