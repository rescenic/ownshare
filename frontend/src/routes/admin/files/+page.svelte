<script>
	import { onMount } from "svelte";
    import FileUploadModal from "../../../lib/components/FileUploadModal.svelte";
    import { PUBLIC_BACKEND_ADDRESS } from "$env/static/public";
    import { invalidateAll } from '$app/navigation';
    import { fetchFileCollections, fetchFileCollection, deleteFileCollections } from "$lib/api.js";

    $: fileCollections = [];
    
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

    onMount(async () => {
        fileCollections = await fetchFileCollections(fetch);
    });

</script>

<!-- Upload File Modal -->

<FileUploadModal bind:showUploadModal on:uploadFinished={async () => {fileCollections = await fetchFileCollections(fetch);}}></FileUploadModal>

<!-- File List -->

<div class="flex justify-between items-center mb-4"> 
    <h1 class="text-4xl font-bold">File Collections</h1>

    <div>
        <button class="btn btn-accent btn-sm" on:click={() => {showUploadModal = true}}>Upload</button>
    </div>
</div>

<div>
    <div class="overflow-x-auto w-full">
        <table class="table">
          <!-- head -->
          <thead>
            <tr>
              <th>Name</th>
              <th>Total Size</th>
              <th>Downloads</th>
              <th>Uploaded by</th>
              <th>Uploaded at</th>
              <th>Expiry Date</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            {#each fileCollections as collection, i}
                <tr class="bg-base-200">
                    <td>{collection.title}</td>
                    <td>{formatBytes(collection.totalSize)}</td>
                    <td>{collection.downloads}/{collection.max_downloads}</td>
                    <td>{collection.uploaded_by.username}</td>
                    <td>{formatDate(collection.uploaded_at)}</td>
                    <td>{formatDate(collection.save_until)}</td>
                    <td class="flex flex-nowrap gap-1">
                        <a class="btn btn-secondary btn-sm p-0 aspect-square" href="/?q={collection.collection_id}">
                            <img src="/icons/link.svg" alt="">   
                        </a>
                        <!-- <button class="btn btn-secondary btn-sm p-0 aspect-square">
                            <img src="/icons/edit.svg" alt="">   
                        </button> -->
                        <button class="btn btn-error btn-sm p-0 aspect-square" on:click={async () => {
                                deleteFileCollections(collection.collection_id);
                                fileCollections = await fetchFileCollections(fetch);
                            }}>
                            <img src="/icons/delete.svg" alt="">   
                        </button>
                    </td>
                </tr>
                <br>
            {/each}
          </tbody>
        </table>
    </div>
</div>

<style>
    .files-grid {
        --auto-grid-min-size: 25rem;
  
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(var(--auto-grid-min-size), 1fr));
        grid-gap: 1rem;
    }
</style>