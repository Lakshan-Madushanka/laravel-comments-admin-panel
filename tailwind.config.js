import filamentPreset from './vendor/filament/support/tailwind.config.preset.js'

export default {
    presets: [filamentPreset],
    content: [
        "./resources/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
    ],

    plugins: [],
}

