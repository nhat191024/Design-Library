<script>
    $(document).ready(function() {
        const searchInput = $('#search-input');
        const suggestionsContainer = $('#search-suggestions');
        const suggestionsList = $('#search-suggestions ul');
        let currentFocus = -1;

        const fetchSuggestions = function(keyword) {
            // This is where you would normally make an AJAX call to your backend
            // For demonstration, we'll use a static list with filtering

            // Example static suggestions
            const sampleSuggestions = [
                @foreach ($tags as $tag)
                    '{{ $tag->name }}',
                @endforeach
                @foreach ($categories as $category)
                    '{{ $category->name }}',
                @endforeach
            ];

            if (!keyword) return [];

            return sampleSuggestions.filter(item =>
                item.toLowerCase().includes(keyword.toLowerCase())
            );
        };

        const showSuggestions = function(suggestions) {
            suggestionsList.empty();

            if (suggestions.length === 0) {
                suggestionsContainer.addClass('hidden');
                return;
            }

            suggestions.forEach(suggestion => {
                const keyword = searchInput.val();
                const highlightedText = highlightKeyword(suggestion, keyword);

                suggestionsList.append(`
                        <li class="px-4 py-2 hover:bg-base-200 cursor-pointer suggestion-item">
                            ${highlightedText}
                        </li>
                    `);
            });

            suggestionsContainer.removeClass('hidden');
        };

        const highlightKeyword = function(text, keyword) {
            if (!keyword) return text;

            const regex = new RegExp(`(${keyword})`, 'gi');
            return text.replace(regex, '<strong class="text-primary">$1</strong>');
        };

        searchInput.on('input', function() {
            const keyword = $(this).val().trim();

            if (keyword.length > 0) {
                const suggestions = fetchSuggestions(keyword);
                showSuggestions(suggestions);
            } else {
                suggestionsContainer.addClass('hidden');
            }

            currentFocus = -1;
        });

        $(document).on('click', '.suggestion-item', function() {
            searchInput.val($(this).text());
            suggestionsContainer.addClass('hidden');
            $('#search-form').submit();
        });

        searchInput.on('keydown', function(e) {
            const items = $('.suggestion-item');

            if (e.keyCode === 40) {
                currentFocus++;
                addActive(items);
            } else if (e.keyCode === 38) {
                currentFocus--;
                addActive(items);
            } else if (e.keyCode === 13 && currentFocus > -1) {
                if (items.length > 0) {
                    e.preventDefault();
                    items[currentFocus].click();
                }
            }
        });

        const addActive = function(items) {
            if (!items) return;

            removeActive(items);

            if (currentFocus >= items.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (items.length - 1);

            items.eq(currentFocus).addClass('bg-base-200');
        };

        const removeActive = function(items) {
            items.removeClass('bg-base-200');
        };

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#search-input, #search-suggestions').length) {
                suggestionsContainer.addClass('hidden');
            }
        });

        if (typeof search !== 'function') {
            window.search = function() {
                $('#search-form').submit();
            };
        }
    });
</script>
