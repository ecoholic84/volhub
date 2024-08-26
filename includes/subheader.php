<nav class="w-full mt-4"
     x-data="{
         tabSelected: 1,
         tabId: $id('tabs'),
         tabButtonClicked(tabButton){
             this.tabSelected = tabButton.id.replace(this.tabId + '-', '');
             this.tabRepositionMarker(tabButton);
         },
         tabRepositionMarker(tabButton){
             this.$refs.tabMarker.style.width=tabButton.offsetWidth + 'px';
             this.$refs.tabMarker.style.height=tabButton.offsetHeight + 'px';
             this.$refs.tabMarker.style.left=tabButton.offsetLeft + 'px';
         }
     }"
     x-init="tabRepositionMarker($refs.tabButtons.firstElementChild);">
    <div class="px-2 sm:px-6 lg:px-8">
        <div class="relative flex h-16 items-center justify-between">
            <!-- Center (slider) -->
            <div class="flex-1 flex justify-center">
                <div x-ref="tabButtons" class="relative inline-grid items-center justify-center h-10 grid-cols-2 p-1 bg-gray-800 rounded-lg select-none">
                    <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button" class="relative z-20 inline-flex items-center justify-center w-24 sm:w-32 h-8 px-3 text-sm font-medium text-gray-300 transition-all rounded-md cursor-pointer whitespace-nowrap">Dashboard</button>
                    <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button" class="relative z-20 inline-flex items-center justify-center w-24 sm:w-32 h-8 px-3 text-sm font-medium text-gray-300 transition-all rounded-md cursor-pointer whitespace-nowrap">Organize</button>
                    <div x-ref="tabMarker" class="absolute left-0 z-10 w-1/2 h-full duration-300 ease-out" x-cloak>
                        <div class="w-full h-full bg-gray-700 rounded-md shadow-sm"></div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</nav>