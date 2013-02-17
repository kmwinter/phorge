<b>Error</b>: {$exception->getMessage()}

{foreach from=$exception->getTrace() item=step}
    <div class="trace-item-detail">
		{$step.file} :  {$step.class} {$step.type} {$step.function}
            <b>line {$step.line}</b>
    </div>

{/foreach}