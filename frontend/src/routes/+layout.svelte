<script>
	import { onMount } from "svelte";
    import "../app.css";
    import { base } from '$app/paths'
    
    onMount(() => {
    })

</script>
  
<div class="app">
    <slot />
</div>