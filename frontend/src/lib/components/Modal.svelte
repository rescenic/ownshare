<script>
	export let showModal; // boolean

	let dialog; // HTMLDialogElement

	$: if (dialog && showModal) dialog.showModal();
</script>

<!-- svelte-ignore a11y-click-events-have-key-events a11y-no-noninteractive-element-interactions -->
<dialog
	bind:this={dialog}
	on:close={() => (showModal = false)}
	on:click|self={() => dialog.close()}
    class="modal"
>
    <div class="modal-box max-w-max">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" on:click={() => dialog.close()}>✕</button>
        <slot/>
    </div>
</dialog>
