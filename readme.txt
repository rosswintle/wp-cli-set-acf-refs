=== WP-CLI Set ACF References ===
Contributors: magicroundabout
Tags: acf, postmeta
Requires at least: 4.9
Tested up to: 4.9.5
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Sets ACF reference fields for postmeta where missing - useful after importing post meta somehow

== Description ==

This plugin adds a WP-CLI command for adding ACF (Advanced Custom Fields) "reference" fields
to postmeta where it is missing.

Why might this be needed? Well, if an ACF field stored data in postmeta it also stores a reference
to the ACF field definition.  So if your postmeta field name/key is `location` then ACF creates
a postmeta entry with the key `_location` and a value of the form `field_XXXXXXXXX`

If you import a load of postmeta, or create it manually, then you will not have the link to the
ACF field definition.  Why is this bad?  Because ACF's `get_field()` function actually applies
a certain amount of interpretation and formatting based on the field definition.

*NOTE:* The command is a bit simple and brute-force in its approach. If you have multiple
ACF fields with the same name it might break. Make a database backup before using.
You have been warned!

== Installation ==

Install this like any other plugin.

== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

== Changelog ==

= 1.0 =
* First version

== Upgrade Notice ==


