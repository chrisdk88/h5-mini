<footer class="bg-[#000000] text-gray-400 text-sm">
  <div
    class="max-w-screen-xl min-h-[70px] mx-auto px-4 py-2 md:py-0 flex flex-col md:flex-row items-center justify-between gap-2 md:gap-6">

    <!-- Copyright -->
    <div class="text-center md:text-left">
      © <?= date("Y") ?>
      <a href="<?= $baseURL ?>" class="hover:underline text-white font-semibold">DLES™</a>. All rights reserved.
    </div>

    <!-- Footer Navigation -->
    <nav class="text-center md:text-right">
      <ul class="flex flex-row items-center gap-4 sm:gap-6">
        <li>
          <a href="<?= $baseURL ?>suggest" class="hover:text-white transition">Suggest a game</a>
        </li>
      </ul>
    </nav>

  </div>
</footer>