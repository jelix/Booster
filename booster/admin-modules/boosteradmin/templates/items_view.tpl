<h1>{@jelix~crud.title.view@}</h1>
{formdatafull $form}


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
    <td>{$version->modified|eschtml}</td>
    <td>{item_status $version->status}</td>
    <td><a href="{jurl 'boosteradmin~items:editVersion', ['itemid'=>$id, 'id'=>$version->id]}">{@booster~main.edit@}</a></td>
</tr>
{/foreach}
    </tbody>
</table>

<p><a href="{jurl 'boosteradmin~items:createVersion', ['itemid'=>$id]}">{@booster~main.add.a.version@}</a></p>