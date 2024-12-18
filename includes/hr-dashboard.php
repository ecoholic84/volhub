<!-- component -->
<!-- component -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script
      defer=""
      src="https://unpkg.com/alpinejs@3.2.3/dist/cdn.min.js"
    ></script>

    <!-- <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.4/dist/tailwind.min.css"> -->
  </head>
  <body class="bg-gray-50" style="">
    <header class="bg-white ">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold tracking-tight text-gray-900">
         SafariHR
          </h1>
        </div>
      </header>
    <div class="min-h-full">
      <nav class="bg-gray-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
     
              <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-4">
                  <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                  <a
                    href="#"
                    class="bg-gray-900 text-white rounded-md px-3 py-2 text-sm font-medium"
                    aria-current="page"
                    >Home</a
                  >
                  <a
                    href="#"
                    class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium"
                    >My info</a
                  >
                  <a
                    href="#"
                    class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium"
                    >Employees</a
                  >
                  <a
                    href="#"
                    class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium"
                    >Pricing</a
                  >
                  <a
                    href="#"
                    class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium"
                    >Blogs</a
                  >
                </div>
              </div>
            </div>
            <div class="hidden md:block">
              <div class="ml-4 flex items-center md:ml-6">


                <!-- Profile dropdown -->
                <div class="relative ml-3">

                    <div class="md:block lg:block sm:hidden max-w-md mx-auto">
                        <div
                          class="relative  flex items-center w-full h-8 border border-gray-500 rounded-sm  bg-gray-600 overflow-hidden"
                        >
                          <div
                            class="grid place-items-center w-12 bg-gray-600 text-gray-300"
                          >
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="h-6 w-6 bg-gray-600"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke="currentColor"
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                              />
                            </svg>
                          </div>
          
                          <input
                            class="bg-gray-600 outline-none text-sm text-gray-300 pr-2"
                            type="text"
                            id="search"
                            placeholder="Search something.."
                          />
                        </div>
                      </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="md:hidden" id="mobile-menu">
          <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
            <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
            <a
              href="#"
              class="bg-gray-900 text-white block rounded-md px-3 py-2 text-base font-medium"
              aria-current="page"
              >Home</a
            >
            <a
              href="#"
              class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium"
              >My info</a
            >
            <a
              href="#"
              class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium"
              >Employees</a
            >
            <a
              href="#"
              class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium"
              >Pricing</a
            >
            <a
              href="#"
              class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium"
              >Blogs</a
            >
          </div>
          <div class="border-t border-gray-700 pb-3 pt-4">
            <div class="flex items-center px-5">
                <div class="md:block lg:block sm:hidden max-w-md mx-auto">
                    <div
                      class="relative  flex items-center w-full h-8 border border-gray-500 rounded-sm  bg-gray-600 overflow-hidden"
                    >
                      <div
                        class="grid place-items-center w-12 bg-gray-600 text-gray-300"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          class="h-6 w-6 bg-gray-600"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                          />
                        </svg>
                      </div>
      
                      <input
                        class="bg-gray-600 outline-none text-sm text-gray-300 pr-2"
                        type="text"
                        id="search"
                        placeholder="Search something.."
                      />
                    </div>
                  </div>
            </div>
 
          </div>
        </div>
      </nav>
<div class="bg-blue-300 flex justify-between">
<div class=" max-w-7xl px-4 py-6 bg-blue-300 sm:px-6 lg:px-8 hidden lg:block md:block">
                                      
   <img class=" flex-1 w-48 h-48 rounded-full shadow-lg" src="https://static.independent.co.uk/2023/09/14/15/WOLFPACK_Gallery_Kristin_10232022_FO_0064_aprRT.jpg?width=1200&height=1200&fit=crop" alt="">
</div>
        <div class="bg-blue-300  max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class=" text-3xl font-sans tracking-tight text-gray-900">
              Charlot Daniel Abbot
            </h1>
            <p class="ml-10">Sr. HR Administrator</p>
          </div>

          <div class="bg-blue-300 mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

            <div class="flex justify-between">
          
              <!-- Content -->
              <div class="flex-1">
                <!-- Rest of content -->
              </div>
          
              <!-- Buttons -->
              <div class="flex space-x-4 hidden lg:block md:block">
                <button class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded-md">Request a Change</button>  
                <button class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded-md">Settings</button>
              </div>
          
            </div>
          
          </div>
        
</div>
      <main>
        <div class=" max-w-7xl py-0 ">
            <div class="md:flex no-wrap md:-mx-2  ">
                <!-- Left Side -->

                <div class=" w-full md:w-3/12 ">

                    <div class=" p-3 ">
                        
                        <ul class="-mt-3 text-sm font-semibold text-gray-500 hover:text-gray-700 py-2 px-3 mt-3 divide-y rounded bg-white ">
                            <li class="items-center py-3">
                                <span>801-724-6600 Ext.1272</span><br/>
                                <span class="ml-auto">415-555-8965</span>

                            </li>
                            <li class="items-center py-3">
                                <h1>Hire date</h1>
                                <span>Jan 19 2017</span><br/>
                                <span class="ml-auto">10m - 15d</span>
                            </li>

                            <li class=" items-center py-3">
                                <span>Full-time</span><br/>
                                <span class="ml-auto">Human Resources</span>
                                <span class="ml-auto">North America</span>
                                <span class="ml-auto">Lindon , Utah</span>


                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Right Side -->
                <div class="w-full mx-2   md:block lg:block md:-mt-24 sm:mt-0">
                    <!-- Profile tab -->
                    <!-- About Section -->
                    <div class="hidden md:block lg:block">
                    <ul class="flex bg-white ">
                        <li class=" mr-1">
                          <a class="rounded-sm bg-white inline-block border-l border-t border-r rounded-t py-2 px-4 text-blue-700 font-semibold shadow-md" href="#">Personal</a>
                        </li>
                        <li class="mr-1">
                          <a class="rounded-sm bg-white inline-block border-l border-t border-r rounded-t py-2 px-4 text-blue-500 hover:text-blue-800 font-semibold" href="#">Job</a>
                        </li>
                        <li class="mr-1">
                          <a class="rounded-sm bg-white inline-block py-2 px-4 border-l border-t border-r rounded-t py-2 px-4 text-blue-500 hover:text-blue-800 font-semibold" href="#">leave</a>
                        </li>
                        <li class="mr-1">
                            <a class="rounded-sm bg-white inline-block py-2 px-4 border-l border-t border-r rounded-t py-2 px-4 text-blue-500 hover:text-blue-800 font-semibold" href="#">Emergency</a>
                          </li>
                          <li class="mr-1">
                            <a class="rounded-sm bg-white inline-block py-2 px-4 border-l border-t border-r rounded-t py-2 px-1 text-blue-500 hover:text-blue-800 font-semibold" href="#">Document</a>
                          </li>
                          <li class="mr-1">
                            <a class="rounded-sm bg-white inline-block py-2 px-4 border-l border-t border-r rounded-t py-2 px-1 text-blue-500 hover:text-blue-800 font-semibold" href="#">Notes</a>
                          </li>
        
                          <li class="mr-1">
                            <a class="bg-white inline-block py-2 px-4 border-l border-t border-r rounded-t text-blue-500 hover:text-blue-800 font-semibold" href="#">Benefits</a>
                          </li>
        
                          <li class="mr-1">
                            <a class="bg-white inline-block py-2 px-4 border-l border-t border-r rounded-t text-blue-500 hover:text-blue-800 font-semibold" href="#">Training</a>
                          </li>
        
                          <li class="mr-1">
                            <a class="bg-white inline-block py-2 px-4 border-l border-t border-r rounded-t text-blue-500 hover:text-blue-800 font-semibold" href="#">performance</a>
                          </li>
                          <li class="mr-1">
                            <a class="bg-white inline-block py-2 px-4 border-l border-t border-r rounded-t text-blue-500 hover:text-blue-800 font-semibold" href="#">Assets</a>
                          </li>
                      </ul>
                      </div>
                    <div class="bg-white p-3  rounded-sm ">
                        
                        <div class="flex items-center space-x-2 font-semibold text-gray-900 leading-8">
                            <span clas="text-green-500">
                                <svg class="h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </span>
                            <span class="tracking-wide px-2">Personal</span>
                        </div>
                        <div class="flex my-2 py-1">
                          <span class="tracking-wide px-2 bg-blue-500 text-white rounded-md shadow-xl">Upload</span>
                          <span class="tracking-wide px-2">Files</span>
                          </div>
                        <!-- the document logo and stuff -->
                    </div>
                    <!-- End of about section -->
        
                    <div class="my-1 "></div>
        
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8 sm:block ">
                        <div class="overflow-hidden border border-gray-200  md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 ">
            
                                <tbody class="bg-white divide-y divide-gray-200 ">
                                    <tr>
                                        <td class="px-4 py-4 text-sm font-medium ">
                                            <div>
                                                <h2 class="font-medium text-gray-800  ">Resume and Applications</h2>
                                            </div>
                                        </td>
                     
                                        <tr>
                                            <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                                <div>
                                                    <h2 class="font-medium text-gray-800  ">Signed Documents</h2>
        
                                                </div>
                                            </td>
                         
               
                                        </tr>             <tr>
                                            <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                                <div>
                                                    <h2 class="font-medium text-gray-800  ">Tasklist Attachments</h2>
        
                                                </div>
                                            </td>
                         
               
                                        </tr>  
                                             <tr>
                                            <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                                <div>
                                                    <h2 class="font-medium text-gray-800  ">Workflow Attachments</h2>
        
                                                </div>
                                            </td>
                         
               
                                        </tr>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End of profile tab -->
                </div>
            </div>
        </div>
      </main>
    </div>
  </body>
</html>