{*
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel">
    <h2>Preview</h2>
    <p>
        <code>
            {$preview_reference}
        </code>
        <h2>Available Placeholders</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th style="width: 1%;">Placeholder</th>
            <th>Description</th>
            <th>Example</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><code>&lcub;num&rcub;</code></td>
            <td>Incremental number</td>
            <td>Starting from 1, increased by 1 for each document type</td>
        </tr>
        <tr>
            <td><code>&lcub;num_y&rcub;</code></td>
            <td>Incremental number in year</td>
            <td>Starting from 1, increased by 1 for each document type in year</td>
        </tr>
        <tr>
            <td><code>&lcub;num_m&rcub;</code></td>
            <td>Incremental number in month</td>
            <td>Starting from 1, increased by 1 for each document type in month</td>
        </tr>
        <tr>
            <th colspan="3">Date related placeholders</th>
        </tr>
        <tr>
            <td><code>&lcub;d&rcub;</code></td>
            <td>Day of the month, 2 digits with leading zeros</td>
            <td>01 to 31</td>
        </tr>
        <tr>
            <td><code>&lcub;j&rcub;</code></td>
            <td>Day of the month without leading zeros</td>
            <td>1 to 31</td>
        </tr>
        <tr>
            <td><code>&lcub;N&rcub;</code></td>
            <td>ISO-8601 numeric representation of the day of the week</td>
            <td>1 (for Monday) through 7 (for Sunday)</td>
        </tr>
        <tr>
            <td><code>&lcub;z&rcub;</code></td>
            <td>The day of the year (starting from 0)</td>
            <td>0 through 365</td>
        </tr>
        <tr>
            <td><code>&lcub;W&rcub;</code></td>
            <td>ISO-8601 week number of year, weeks starting on Monday</td>
            <td>Example: 42 (the 42nd week in the year)</td>
        </tr>
        <tr>
            <td><code>&lcub;m&rcub;</code></td>
            <td>Numeric representation of a month, with leading zeros</td>
            <td>01 through 12</td>
        </tr>
        <tr>
            <td><code>&lcub;n&rcub;</code></td>
            <td>Numeric representation of a month, without leading zeros</td>
            <td>1 through 12</td>
        </tr>
        <tr>
            <td><code>&lcub;Y&rcub;</code></td>
            <td>A full numeric representation of a year, 4 digits</td>
            <td>Examples: 1999 or 2003</td>
        </tr>
        <tr>
            <td><code>&lcub;y&rcub;</code></td>
            <td>A two digit representation of a year</td>
            <td>Examples: 99 or 03</td>
        </tr>
        </tbody>
    </table>
    </p>
</div>

