import filamentPreset from './filament.preset.js'

export default {
    presets: [filamentPreset],
    content: [
        "./resources/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
    ],

    plugins: [],
}

