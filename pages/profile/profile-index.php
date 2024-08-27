<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
    [x-cloak] {
        display: none
    }
    </style>
    <!-- Include the Alpine library on your page -->
    <script src="https://unpkg.com/alpinejs" defer></script>
    <!-- Include the TailwindCSS library on your page -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-br from-gray-900 via-black to-gray-800 overflow-x-hidden">
    <div class="flex flex-col items-center justify-center w-full max-w-full">
        <h1 x-data="{
startingAnimation: { opacity: 0, scale: 4 },
endingAnimation: { opacity: 1, scale: 1, stagger: 0.07, duration: 1, ease: 'expo.out' },
addCNDScript: true,
animateText() {
$el.classList.remove('invisible');
gsap.fromTo($el.children, this.startingAnimation, this.endingAnimation);
},
splitCharactersIntoSpans(element) {
text = element.innerHTML;
modifiedHTML = [];
for (var i = 0; i < text.length; i++) {
attributes = '';
if(text[i].trim()){ attributes = 'class=\'inline-block\''; }
modifiedHTML.push('<span ' + attributes + '>' + text[i] + '</span>');
}
element.innerHTML = modifiedHTML.join('');
},
addScriptToHead(url) {
script = document.createElement('script');
script.src = url;
document.head.appendChild(script);
}
}" x-init="
splitCharactersIntoSpans($el);
if(addCNDScript){
addScriptToHead('https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js');
}
gsapInterval = setInterval(function(){
if(typeof gsap !== 'undefined'){
animateText();
clearInterval(gsapInterval);
}
}, 5);
" class="invisible block text-6xl font-bold custom-font text-white mb-4">
            Welcome
        </h1>
        <div class="text-2xl text-white">
            Happy Volunteering
        </div>
    </div>
    <button type="button" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium tracking-wide transition-colors duration-200 bg-white border rounded-md text-neutral-500 hover:text-neutral-700 border-neutral-200/70 hover:bg-neutral-100 active:bg-white focus:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-200/60 focus:shadow-outline">
    Create Profile
</button>
</body>

</html>