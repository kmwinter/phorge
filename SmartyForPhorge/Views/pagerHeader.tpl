

[page 
{foreach from=$pager->getPageNumbers() item=link key=num}
	{if $link != ""}
		<a class="small-link" href="{$link}">
			{$num}
		</a>
	{else}
		<span>{$num}</span>
	{/if}
{/foreach}]

{if $pager->getPage() > 1} 	
	[<a class="small-link" href="{getCurrentUrl page=$pager->getPage()-1}">
		previous
	</a>]
{else}
	[<span class="small">previous</span>]
{/if}


{if $pager->getPage() < $pager->numberOfPages() && $pager->numberOfPages() > 1} 	
	[<a class="small-link" href="{getCurrentUrl page=$pager->getPage()+1}">
		next
	</a>]
{else}
	[<span class="small">next</span>]	
{/if}		    

{if $pager->getPage() != 1} 	
	[<a class="small-link" href="{getCurrentUrl page=1}">
		first
	</a>]
{else}
	[<span class="small">first</span>]
{/if}
{if $pager->getPage() != $pager->numberOfPages() && $pager->numberOfPages() > 1} 	
	[<a class="small-link" href="{getCurrentUrl page=$pager->numberOfPages()}">
		last
	</a>]
{else}
	[<span class="small">last</span>]	
{/if}	

<br><br>