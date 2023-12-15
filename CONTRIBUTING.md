Contribution Guide
==================

Please take a moment to review this document in order to make the contribution
process easy and effective for everyone involved.

Following these guidelines helps to communicate that you respect the time of
the developers managing and developing this open source project. In return,
they should reciprocate that respect in addressing your issue or assessing
patches and features.

If you are tying to report a possible security vulnerability in Charcoal,
please see our [security policy][SECURITY.md] for more information.

Everyone interacting with Charcoal is expected to follow
the [code of conduct][CODE_OF_CONDUCT.md].

1. Charcoal utilizes coding conventions (indentation, comments, structures)
   and adheres to PSR-1, PSR-3, PSR-4, PSR-6, PSR-7, PSR-11, PSR-12, and PSR-16.

2. Charcoal is meant to be lean and fast with very few dependencies.
   This means that not every feature request will be accepted.

3. Charcoal has a minimum PHP version requirement of PHP 7.4.
   Pull requests must not require a PHP version greater than PHP 7.4
   unless the feature is only utilized conditionally and the file can
   be parsed by PHP 7.4.

4. All pull requests must include unit tests ([PHPUnit]) to ensure
   the change works as expected and to prevent regressions.

## Using the issue tracker

The issue tracker is the preferred channel for [bug reports](#bug-reports),
[features requests](#feature-requests) and [submitting pull requests](#pull-requests),
but please respect the following restrictions:

* Please **do not** use the issue tracker for personal support requests.
* Please **do not** derail or troll issues. Keep the discussion on topic and
  respect the opinions of others.

## Bug reports

A bug is a _demonstrable problem_ that is caused by the code in the repository.
Good bug reports are extremely helpful — thank you!

Guidelines for bug reports:

1. **Use the central repository issue search** — 
   Check if the issue has already been reported.

2. **Check if the issue has been fixed** — 
   Try to reproduce it using the latest `main`
   or development branch in the repository.

3. **Isolate the problem** — 
   Make sure that the code in the repository
   is _definitely_ responsible for the issue.

A good bug report shouldn't leave others needing to chase you up for more
information. Please try to be as detailed as possible in your report.

## Feature requests

Feature requests are welcome. But take a moment to find out whether your idea
fits with the scope and aims of the project. It's up to *you* to make a strong
case to convince the Charcoal developers of the merits of this feature. Please
provide as much detail and context as possible.

## Pull requests

Good pull requests - patches, improvements, new features - are a fantastic help.
They should remain focused in scope and avoid containing unrelated commits.

**Please ask first** before embarking on any significant pull request (e.g.
implementing features, refactoring code), otherwise you risk spending a lot of
time working on something that the developers might not want to merge into the
project.

Adhering to the following this process is the best way to get your work merged:

1. [Fork][gh-fork-repository] the repository, clone your fork,
   and configure the remotes:

   ```bash
   # Clone your fork of the repository into the current directory
   git clone https://github.com/<your-username>/<repo-name>
   # Navigate to the newly cloned directory
   cd <repo-name>
   # Assign the original repository to a remote called "upstream"
   git remote add upstream https://github.com/<upsteam-owner>/<repo-name>
   ```

2. If you cloned a while ago, get the latest changes from upstream:

   ```bash
   git checkout <dev-branch>
   git pull upstream <dev-branch>
   ```

3. Create a new topic branch (off the main project development branch) to
   contain your feature, change, or fix:

   ```bash
   git checkout -b <topic-branch-name>
   ```

4. Commit your changes in logical chunks. Please adhere to these
   [git commit message guidelines][tbaggery.com-2008-04-19]
   or your code is unlikely be merged into the main project.
   Use Git's [interactive rebase][gh-rebase]
   feature to tidy up your commits before making them public.

5. Locally merge (or rebase) the upstream development branch into your topic branch:

   ```bash
   git pull [--rebase] upstream <dev-branch>
   ```

6. Push your topic branch up to your fork:

   ```bash
   git push origin <topic-branch-name>
   ```

10. [Open a Pull Request][gh-create-pull-request]
    with a clear title and description.

## Attribution

This Contribution Guide is adapted from
[Roots' Contribution Guide](https://github.com/roots/.github/blob/f33fd27/CONTRIBUTING.md)
(version 2019-03-12) and
[Guzzle's Contribution Guide](https://github.com/guzzle/guzzle/blob/84779a5/docs/overview.rst)
(version 2022-07-31).

[CODE_OF_CONDUCT.md]:      https://github.com/charcoalphp/.github/blob/main/CODE_OF_CONDUCT.md
[gh-create-pull-request]:  https://help.github.com/articles/using-pull-requests/
[gh-fork-repository]:      http://help.github.com/fork-a-repo/
[gh-rebase]:               https://help.github.com/articles/interactive-rebase
[PHPUnit]:                 https://github.com/sebastianbergmann/phpunit/
[PSR-1]:                   https://www.php-fig.org/psr/psr-1/
[PSR-3]:                   https://www.php-fig.org/psr/psr-3/
[PSR-4]:                   https://www.php-fig.org/psr/psr-4/
[PSR-6]:                   https://www.php-fig.org/psr/psr-6/
[PSR-7]:                   https://www.php-fig.org/psr/psr-7/
[PSR-11]:                  https://www.php-fig.org/psr/psr-11/
[PSR-12]:                  https://www.php-fig.org/psr/psr-12/
[PSR-16]:                  https://www.php-fig.org/psr/psr-16/
[SECURITY.md]:             https://github.com/charcoalphp/.github/blob/main/SECURITY.md
[tbaggery.com-2008-04-19]: http://tbaggery.com/2008/04/19/a-note-about-git-commit-messages.html
