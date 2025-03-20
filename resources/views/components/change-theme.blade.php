@props([
    'lightTheme' => 'light',
    'darkTheme' => 'dark',
])

<div x-data="{
        theme: $persist(window.matchMedia('(prefers-color-scheme: dark)').matches ? '{{ $darkTheme }}' : '{{ $lightTheme }}').as('mary-theme'),
        init() {
            this.setToggle()
        },
        setToggle() {
            document.documentElement.setAttribute('data-theme', this.theme)
            document.documentElement.setAttribute('class', this.theme)
            this.$dispatch('theme-changed', this.theme)
        },
        applyTheme(theme) {
            this.theme = theme
            this.setToggle()
        }
    }"
    @mary-toggle-theme.window="applyTheme($event.detail)"
    {{ $attributes->class("theme-toggle") }}
>
    <select x-on:change="applyTheme($event.target.value)" class="select w-full max-w-xs select-bordered select-xs">
        <option value="light" :selected="theme === 'light'">Light</option>
        <option value="dark" :selected="theme === 'dark'">Dark</option>
        <option value="cupcake" :selected="theme === 'cupcake'">Cupcake</option>
        <option value="bumblebee" :selected="theme === 'bumblebee'">Bumblebee</option>
        <option value="emerald" :selected="theme === 'emerald'">Emerald</option>
        <option value="corporate" :selected="theme === 'corporate'">Corporate</option>
        <option value="synthwave" :selected="theme === 'synthwave'">Synthwave</option>
        <option value="retro" :selected="theme === 'retro'">Retro</option>
        <option value="cyberpunk" :selected="theme === 'cyberpunk'">Cyberpunk</option>
        <option value="valentine" :selected="theme === 'valentine'">Valentine</option>
        <option value="halloween" :selected="theme === 'halloween'">Halloween</option>
        <option value="garden" :selected="theme === 'garden'">Garden</option>
        <option value="forest" :selected="theme === 'forest'">Forest</option>
        <option value="aqua" :selected="theme === 'aqua'">Aqua</option>
        <option value="lofi" :selected="theme === 'lofi'">Lofi</option>
        <option value="pastel" :selected="theme === 'pastel'">Pastel</option>
        <option value="fantasy" :selected="theme === 'fantasy'">Fantasy</option>
        <option value="wireframe" :selected="theme === 'wireframe'">Wireframe</option>
        <option value="black" :selected="theme === 'black'">Black</option>
        <option value="luxury" :selected="theme === 'luxury'">Luxury</option>
        <option value="dracula" :selected="theme === 'dracula'">Dracula</option>
        <option value="cmyk" :selected="theme === 'cmyk'">CMYK</option>
        <option value="autumn" :selected="theme === 'autumn'">Autumn</option>
        <option value="business" :selected="theme === 'business'">Business</option>
        <option value="acid" :selected="theme === 'acid'">Acid</option>
        <option value="lemonade" :selected="theme === 'lemonade'">Lemonade</option>
        <option value="night" :selected="theme === 'night'">Night</option>
        <option value="coffee" :selected="theme === 'coffee'">Coffee</option>
        <option value="winter" :selected="theme === 'winter'">Winter</option>
        <option value="dim" :selected="theme === 'dim'">Dim</option>
        <option value="nord" :selected="theme === 'nord'">Nord</option>
        <option value="sunset" :selected="theme === 'sunset'">Sunset</option>
    </select>
</div>
<script type="text/javascript" defer>
    document.documentElement.setAttribute("data-theme", localStorage.getItem("mary-theme")?.replaceAll("\"", ""))
    document.documentElement.setAttribute("class", localStorage.getItem("mary-theme")?.replaceAll("\"", ""))
</script>