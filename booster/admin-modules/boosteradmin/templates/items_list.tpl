<h1>{@jelix~crud.title.list@}</h1>
{if isset($filterForm) && $filterForm}
<h2>{@jelix~crud.title.filter@}</h2>
{form $filterForm, $filterAction, [], 'html', [ 'method' => 'GET']}
<table class="jforms-table">
{formcontrols $filterFields}
    <tr>
        <th scope="row">{ctrl_label}</th>
        <td>{ctrl_control}</td>
    </tr>
{/formcontrols}
</table>
<div>{formreset}{formsubmit}</div>
{/form}

    <p><a href="{jurl $createAction}" class="crud-link">{@jelix~crud.link.create.record@}</a>.</p>

    <h2>{jlocale 'jelix~crud.title.results', $recordCount}</h2>
{/if}

<table class="records-list">
<thead>
<tr>
    {foreach $properties as $propname}
        <th>
            {if $showPropertiesOrderLinks && array_key_exists($propname, $propertiesForListOrder)}
            <a href="{jurl '#~#', array($offsetParameterName=>$page, 'listorder'=>$propname)}"
               class="view-order{if isset($sessionForListOrder[$propname])} {if $sessionForListOrder[$propname] == 'asc'} order-asc{elseif $sessionForListOrder[$propname] == 'desc'} order-desc{/if}{/if}">
            {/if}
            {if isset($controls[$propname]) && $controls[$propname]->label}{$controls[$propname]->label|eschtml}{else}{$propname|eschtml}{/if}
            {if $showPropertiesOrderLinks && array_key_exists($propname, $propertiesForListOrder)}</a>{/if}
        </th>
    {/foreach}
    <th>&nbsp;</th>
</tr>
</thead>
<tbody>
{foreach $list as $record}
<tr class="{cycle array('odd','even')}">
    {foreach $properties as $propname}
        {if $propname == 'type_id'}
            <td>{$record->type_name|eschtml}</td>
        {elseif $propname == 'item_by'}
            <td>{siteuser $record->item_by}</td>
        {elseif $propname == 'status'}
            <td>{item_status $record->status}</td>
        {elseif $propname == 'dev_status'}
            <td>{item_dev_status $record->dev_status}</td>
        {elseif $propname == 'reviewed'}
            <td>{if $record->reviewed}{@jelix~ui.buttons.yes@}{else}-{/if}</td>
        {else}
            <td>{if $record->$propname}{$record->$propname|eschtml}{/if}</td>
        {/if}
    {/foreach}
    <td>
        <a href="{jurl $viewAction,array('id'=>$record->$primarykey)}">{@jelix~crud.link.view.record@}</a>
    </td>
</tr>
{/foreach}
</tbody>
</table>
{if $recordCount > $listPageSize}
<p class="record-pages-list">{@jelix~crud.title.pages@} : {pagelinks $listAction, array(),  $recordCount, $page, $listPageSize, $offsetParameterName }</p>
{/if}

<form action="{jurl 'boosteradmin~items:startReview'}" id="review-form">
    <p><button>{@boosteradmin~admin.review.start@}</button></p>
</form>