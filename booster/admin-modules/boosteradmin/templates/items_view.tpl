<h1>{@booster~main.item@}</h1>

{formdata $form}
<table class="jforms-table">
    {formcontrols }
        {ifctrl 'url_website', 'url_repo', 'url_download'}
        <tr>
            <th>{ctrl_label}</th>
            <td>{ifctrl_value ''}{else}<a href="{ctrl_rawvalue}" target="_blank">{ctrl_rawvalue}</a>{/ifctrl_value}</td>
        </tr>
        {else}{ifctrl 'item_composer_id'}
            <tr>
                <th>{ctrl_label}</th>
                <td>{ifctrl_value ''}{else}<a href="https://packagist.org/packages/{ctrl_rawvalue}" target="_blank">{ctrl_rawvalue}</a>{/ifctrl_value}</td>
            </tr>
        {else}
        <tr>
            <th>{ctrl_label}</th>
            <td>{ctrl_value}</td>
        </tr>
        {/ifctrl}
        {/ifctrl}
    {/formcontrols}
</table>
{/formdata}


<ul class="crud-links-list">
    <li><a href="{jurl $editAction, array('id'=>$id, $offsetParameterName=>$page)}" class="crud-link">{@jelix~crud.link.edit.record@}</a></li>
    <li><a href="{jurl $deleteAction, array('id'=>$id, $offsetParameterName=>$page)}" class="crud-link" onclick="return confirm('{@jelix~crud.confirm.deletion@}')">{@jelix~crud.link.delete.record@}</a></li>
    <li><a href="{jurl $listAction, array($offsetParameterName=>$page)}" class="crud-link">{@jelix~crud.link.return.to.list@}</a></li>
</ul>

<h2>{@boosteradmin~admin.versions@}</h2>

<table class="jforms-table">
    <thead>
    <tr>
        <th>{@booster~main.version_name@}</th>
        <th>{@booster~main.version_date@}</th>
        <th>{@booster~main.stability@}</th>
        <th>{@booster~main.jelix.version.min@}</th>
        <th>{@booster~main.jelix.version.max@}</th>
        <th>{@booster~main.download@}</th>
        <th>{@boosteradmin~admin.items_list.date_modified@}</th>
        <th>{@booster~main.status@}</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
{foreach $versions as $version}
<tr>
    <th>{$version->version_name|eschtml}</th>
    <td>{$version->version_date|jdatetime:'db_date':'lang_date'}</td>
    <td>{$version->stability|eschtml}</td>
    <td>{$version->version_min|eschtml}</td>
    <td>{$version->version_max|eschtml}</td>
    <td>{if $version->download_url}
        {if $version->filename}
        <a href="{$version->download_url|eschtml}" target="_blank">{$version->filename|eschtml}</a>
        {else}
        <a href="{$version->download_url|eschtml}" target="_blank">{$version->download_url|eschtml}</a>
        {/if}
    {/if}</td>
    <td>{$version->modified|eschtml}</td>
    <td>{item_status $version->status}</td>
    <td><a href="{jurl 'boosteradmin~items:editVersion', ['itemid'=>$id, 'id'=>$version->id]}">{@booster~main.edit@}</a></td>
</tr>
{/foreach}
    </tbody>
</table>

<p><a href="{jurl 'boosteradmin~items:createVersion', ['itemid'=>$id]}">{@booster~main.add.a.version@}</a></p>

{if $record->reviewed}
<p>{@boosteradmin~admin.review.project.on@} {$record->review_date|jdatetime:'db_date':'lang_date'}</p>
{else}
<form action="{jurl 'boosteradmin~items:reviewed', array('id'=>$id)}">
    <p><button>{@boosteradmin~admin.reviewed@}</button></p>
</form>
{/if}

