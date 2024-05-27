<script>
    import { goto } from '$app/navigation';
	import { onMount } from "svelte";
    import { getOption, setOption } from "$lib/api.js";
    import CreateUserModal from "../../../lib/components/CreateUserModal.svelte";


    let pageError = "";
    let showCreateUserModal = false;

    export let data;

</script>

<CreateUserModal bind:showModal={showCreateUserModal}></CreateUserModal>

<div class="flex justify-between items-center mb-4"> 
    <h1 class="text-4xl font-bold">Users</h1>

    <div>
        <button class="btn btn-accent btn-sm" on:click={() => {showCreateUserModal = true}}>Create User</button>
    </div>
</div>

<span class="mb-4 text-red-500">{pageError}</span>

<div class="overflow-x-auto w-full">
    <table class="table">
      <!-- head -->
      <thead>
        <tr>
          <th>Id</th>
          <th>Username</th>
          <th>E-Mail</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
        {#each data.users as user, i}
            <tr class="bg-base-200">
                <td>{user.id}</td>
                <td>{user.username}</td>
                <td>{user.email}</td>
                <td>{user.role}</td>
                <td>
                    <button class="btn btn-secondary btn-sm p-0 aspect-square">
                        <img src="/icons/edit.svg" alt="">   
                    </button>
                    <!-- <button class="btn btn-error btn-sm p-0 aspect-square" on:click={() => {
                            deleteFileCollections(collection.collection_id);
                            invalidateAll();
                        }}>
                        <img src="/icons/delete.svg" alt="">   
                    </button> -->
                </td>
            </tr>
            <br>
        {/each}
      </tbody>
    </table>
</div>
