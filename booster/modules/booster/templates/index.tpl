{if isset($search_results)}
    {if empty($search_results)}
        <div id="article" class="error no-result">
            <h2>{@main.search.no.results@}</h2>
            <p><a href="{jurl 'booster~default:index'}">{@main.return.to.index@}</a></p>
        </div>
    {else}
        {assign $show_all_versions = false}
        {foreach $search_results as $data}
            {include 'booster~view_item'}
        {/foreach}
    {/if}
{else}
    <h1>{@booster~main.projects.recommended@}</h1>
    {zone 'booster~recommended_projects'}
    <h1>{@booster~main.last.updated@}</h1>
    <div id="homepage-wrapper">
        {zone 'booster~homepage_block', array('type'=> 2)}
        {zone 'booster~homepage_block', array('type'=> 5)}
        {zone 'booster~homepage_block', array('type'=> 3)}
        {zone 'booster~homepage_block', array('type'=> 1)}
        {zone 'booster~homepage_block', array('type'=> 4)}
    </div>

{/if}
