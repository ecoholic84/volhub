<style>

input:checked ~ .dot {
transform: translateX(100%);
/* background-color: #132b50; */
}
</style>
<div class="bg-white dark:bg-gray-900 min-h-screen w-full justify-center flex flex-row items-center transition-all ease-in-out">
<div class="flex flex-row justify-between toggle">
<label for="dark-toggle" class="flex items-center cursor-pointer">
    <div class="relative">
    <input type="checkbox" name="dark-mode" id="dark-toggle" class="checkbox hidden">
    <div class="block border-[1px] dark:border-white border-gray-900 w-14 h-8 rounded-full"></div>
    <div class="dot absolute left-1 top-1 dark:bg-white bg-gray-800 w-6 h-6 rounded-full transition"></div>
    </div>
    <div class="ml-3 dark:text-white text-gray-900 font-medium">
    Dark Mode
    </div>
</label>
</div>
</div>
<script>
tailwind.config = {
darkMode: 'class',
}
</script>
<script>
function darkModeListener() {
document.querySelector("html").classList.toggle("dark");
}

document.querySelector("input[type='checkbox']#dark-toggle").addEventListener("click", darkModeListener);

</script>