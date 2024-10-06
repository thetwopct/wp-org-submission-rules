# WordPress.org-specific code sniffs

When submitting a plugin to the WordPress.org repo, there are several checks that the plugin review team apply to your plugin, but which are not fully covered by WordPress Coding Standards or included in the Plugin Check (PCP) plugin.

This is a Code Sniff ruleset to try and handle these missing checks.

## Install

```
composer require-dev thetwopct/wp-org-submission-rules
```

Check that the ruleset (WPOrgSubmissionRules) is now installed:

```
phpcs -i
```

Add it to your custom .phpcs.xml file to include in your sniffs:

```
<rule ref="WPOrgSubmissionRules"/>
```

or access the standard directly from the command line as per other standards:

```
phpcs --standard=WPOrgSubmissionRules your-file.php
```

## What are you detecting

- Use wp_enqueue commands
- Internationalization: Don't use variables or defines as text, context or text domain parameters.
- Generic function/class/define/namespace/option names