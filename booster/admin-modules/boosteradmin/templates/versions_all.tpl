<h1>{@boosteradmin~admin.versions.validated@}</h1>
{if $datas->rowCount() > 0}
<table class="records-list">
    <thead>
        <tr>
            <th>{@boosteradmin~admin.versions_list.name@}</th>
            <th>{@boosteradmin~admin.versions_list.version@}</th>
            <th>{@boosteradmin~admin.versions_list.date_created@}</th>
            <th>{@boosteradmin~admin.item_by@}</th>
            <th>{@boosteradmin~admin.action@}</th>
        </tr>
    </thead>
    <tbody>
{foreach $datas as $data}
        <tr class="{cycle array('even','odd')}">
            <td>{$data->name|eschtml}</td>
            <td><a href="{jurl 'boosteradmin~versions:editnew',array('id'=>$data->version_id)}">{$data->version_name|eschtml}</a></td>
            <td>{$data->created|jdatetime}</td>
            <td>{zone 'booster~author', array('id' => $data->item_by)}</td>
            <td><a href="{jurl 'boosteradmin~versions:delete',array('id'=>$data->version_id)}"
                   alt="{@boosteradmin~admin.action.delete@}"
                   onclick="return confirm('{jlocale 'boosteradmin~admin.confirm.deletion',array($data->name)}')">{@boosteradmin~admin.action.delete@}</a></td>
        </tr>
{/foreach}
    </tbody>
</table>
{/if}
