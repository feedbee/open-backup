Open Backup
===========

*WARNING: Open Backup tool is currently under heavy development and is not ready even for testing purposes.*

About this tool
---------------

Open Backup is a free software for creating and restoring backups. It's main purpose is to give user simple declarative
interface for backup structure and credentials definition. All real commands generated basing on this config.

This software written in PHP. PHP-based engine used to parse config, generate and run commands, which works with data.
No data passed by PHP be default. For example, MySQL backups created using `mysqldump` and restored using `mysql`
CLI utilities.

Using Open Backup it's possible to backup/restore any data which is possible using simple UNIX/Linux tools. All actions
at the end performed with command shell (by default). Open Backup layes used only to parse config and generate
corresponding commands sequences. User have to write manually just one declaration. All backup and restore commands
will be generated automatically.

Using
-----

*Basic usage:* `php backup.php --mode backup|restore`

Command
-------

*Open Backup Command* is not the same as a shell command. It's a plugin which can generate two types of shell command:
backup and restore. Backup command executed to create backup files. Restore commands executed to extract backup-ed data.
This commands are opposite.

Open Backup has most popular commands included out of the box. This commands can be tuned with parameters. User can
create new custom functionality writing custom commands.

Config structure
----------------
[@TODO]

Installation
------------
Open Backup tool can be installed using Composer from GitHub repository.


License
--------
Open Backup is published under MIT License

The MIT License (MIT)

Copyright (c) 2013 Valera Leontyev

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.