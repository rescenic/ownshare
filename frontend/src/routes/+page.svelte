<script>
    import { PUBLIC_BACKEND_ADDRESS } from "$env/static/public"
</script>

<main class="w-full h-screen flex items-center justify-center">

    { PUBLIC_BACKEND_ADDRESS }

    <div class="card w-96 bg-base-100 shadow-xl">
        <div class="card-body">
          <h2 class="card-title">Shoes!</h2>
          <p>If a dog chews shoes whose shoes does he choose?</p>
          <div class="card-actions justify-end">
            <button class="btn btn-primary">Buy Now</button>
          </div>
        </div>
      </div>

</main>