<script>
	import { onMount } from "svelte";
    import FileUploadModal from "../../../lib/components/FileUploadModal.svelte";
    import { PUBLIC_BACKEND_ADDRESS } from "$env/static/public";
    import { deleteFileCollections } from "$lib/api.js";
    import { invalidateAll } from '$app/navigation';

    export let data;
    
    let showUploadModal = false;

    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function formatDate(date) {
        const [datePart] = date.split(' '), [year, month, day] = datePart.split('-');
        return `${day}.${month}.${year}`;
    }

</script>

<!-- Upload File Modal -->

<FileUploadModal bind:showUploadModal></FileUploadModal>

<!-- File List -->

<div class="flex justify-between items-center mb-4"> 
    <h1 class="text-4xl font-bold">Files</h1>

    <div>
        <button class="btn btn-accent btn-sm" on:click={() => {showUploadModal = true}}>Upload</button>
    </div>
</div>

<ul class="files-grid w-full">
    {#each data.fileCollections as collection, i }
        <li class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <div class="flex justify-between items-center gap-2">
                    <h2 class="card-title text-xl truncate inline-block" style="width:calc(100%);">{collection.title}</h2>
                </div>

                <dl class="flex gap-2 justify-between flex-col">
                    <div class="flex gap-0.5">
                        <dt class="text-neutral">Files:</dt>
                        <dd>{collection.files.length}</dd>
                    </div>

                    <div class="flex gap-0.5">
                        <dt class="text-neutral">Total:</dt>
                        <dd>{formatBytes(collection.totalSize)}</dd>
                    </div>

                    <div class="flex gap-0.5">
                        <dt class="text-neutral">Expiry:</dt>
                        <dd>{formatDate(collection.save_until)}</dd>
                    </div>

                    <div class="flex gap-0.5">
                        <dt class="text-neutral">Downloads:</dt>
                        <dd>{collection.downloads}/{collection.max_downloads}</dd>
                    </div>

                    <div class="flex gap-0.5">
                        <dt class="text-neutral">Uploaded by:</dt>
                        <dd>{collection.uploaded_by.username}</dd>
                    </div>

                    <div class="flex gap-0.5">
                        <dt class="text-neutral">Uploaded at:</dt>
                        <dd>{formatDate(collection.uploaded_at)}</dd>
                    </div>
                </dl>


                <div class="card-actions justify-between items-center">
                    <div>
                        <a class="btn btn-secondary btn-sm p-0 aspect-square" href="/{collection.collection_id}">
                            <img src="/icons/link.svg" alt="">   
                        </a>
                        <button class="btn btn-secondary btn-sm p-0 aspect-square">
                            <img src="/icons/edit.svg" alt="">   
                        </button>
                        <button class="btn btn-error btn-sm p-0 aspect-square" on:click={() => {
                                deleteFileCollections(collection.collection_id);
                                invalidateAll();
                            }}>
                            <img src="/icons/delete.svg" alt="">   
                        </button>
                    </div>
                </div>
            </div>
        </li>
    {/each}
</ul>

<style>
    .files-grid {
        --auto-grid-min-size: 25rem;
  
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(var(--auto-grid-min-size), 1fr));
        grid-gap: 1rem;
    }
</style>