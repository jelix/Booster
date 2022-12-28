<div id="booster_tagscloud">
    <ul id="tagscloud">
        {foreach $tags as $t}
            <li>
            	<a href="{jurl 'booster~default:index', array('search'=>1, 'tags'=>$t->tag_name)}" class="tag{$size[$t->tag_id]}" title="{jlocale 'booster~main.show.all.items.with.tag', array($t->tag_name)}">
            		{$t->tag_name}</a>
            </li>
        {/foreach}
    </ul>
</div>
