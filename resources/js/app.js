import Alpine from "alpinejs";
import flatpickr from "flatpickr";
import Quill from "quill";
import * as FilePond from "filepond";
import { createPopper } from "@popperjs/core";
import focus from "@alpinejs/focus";
import './bootstrap'
import Turbolinks from "turbolinks";
// Import the plugin code
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

// Import the plugin styles
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

Alpine.plugin(focus);

window.flatpickr = flatpickr;
window.FilePond = FilePond;
window.Quill = Quill;
window.createPopper = createPopper;
window.Alpine = Alpine;

window.Alpine.start();

// Register the plugin
FilePond.registerPlugin(FilePondPluginImagePreview);

Turbolinks.start()
