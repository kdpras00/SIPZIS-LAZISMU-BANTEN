import re

with open("resources/views/layouts/app.blade.php", "r") as f:
    content = f.read()

# Add Select2 CSS in the head
css = """    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Select2 Tailwind Customization */
        .select2-container .select2-selection--single {
            height: 44px !important;
            border-color: #e8e0d6 !important;
            border-radius: 0.75rem !important; /* rounded-xl */
            display: flex !important;
            align-items: center !important;
            padding-left: 0.5rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px !important;
            right: 10px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1c0f0a !important;
            font-size: 0.75rem !important; /* text-xs */
            font-weight: 500 !important;
            line-height: 44px !important;
        }
        .select2-container--open .select2-dropdown--below {
            border-color: #e8e0d6 !important;
            border-radius: 0 0 0.75rem 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1) !important;
        }
        .select2-results__option {
            font-size: 0.75rem !important;
            padding: 10px 16px !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #c2410c !important; /* orange-700 */
        }
        .select2-search--dropdown .select2-search__field {
            border-radius: 0.5rem !important;
            border-color: #e8e0d6 !important;
            padding: 8px !important;
            font-size: 0.75rem !important;
            outline: none !important;
        }
        .select2-search--dropdown .select2-search__field:focus {
            border-color: #c2410c !important;
        }
    </style>
"""
content = content.replace("</head>", css + "</head>")

# Add Select2 JS in the body before @stack('scripts')
js = """    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Apply Select2 to all select elements by default, except those with 'no-select2' class
            $('select:not(.no-select2)').select2({
                width: '100%',
                language: {
                    noResults: function() {
                        return "Data tidak ditemukan";
                    }
                }
            });
        });
    </script>
"""
content = content.replace("    @stack('scripts')", js + "    @stack('scripts')")

with open("resources/views/layouts/app.blade.php", "w") as f:
    f.write(content)
