# Release Workflow

Specify the release version.

```
VERSION=1.1.0
```

## Issues

Check issues at https://github.com/Icinga/icingaweb2-module-pnp

## Authors

Update the [.mailmap](.mailmap) and [AUTHORS](AUTHORS) files:

```
git checkout master
git log --use-mailmap | grep ^Author: | cut -f2- -d' ' | sort | uniq > AUTHORS
```

## Update metadata

Edit and update [module.info](module.info).

## Changelog

Update the [CHANGELOG.md](CHANGELOG.md) file.

Uses [github_changelog_generator](https://github.com/skywinder/github-changelog-generator)

```
export CHANGELOG_GITHUB_TOKEN=xxx
github_changelog_generator --future-release v$VERSION
```

Check if the file has been updated correctly.

## Git Tag

Commit these changes to the "master" branch:

```
git commit -v -a -m "Release version $VERSION"
git push origin master
```

And tag it with a signed tag:

```
git tag -s -m "Version $VERSION" v$VERSION
```

Push the tag.

```
git push --tags
```

## GitHub Release

Create a new release for the newly created Git tag.
https://github.com/Icinga/icingaweb2-module-pnp/releases
