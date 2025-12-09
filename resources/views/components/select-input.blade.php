
<x-input-label for="{{ $name }}" :value="$label" />
<input type="text" autocomplete="nope" name="{{ $name }}" id="{{ $name }}"  {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!} />
<ul id="searchResults" class="term-list hidden"></ul>


@section('scripts')
    <script>
        var dataStates = {!! json_encode($options) !!};
        let searchIndex = mergeStatesAndMunicipalities(dataStates);
        var input = document.getElementById("{{ $name }}"),
            ul = document.getElementById("searchResults"),
            inputTerms, termsArray, prefix, terms, results, sortedResults;


        var search = function() {
        
            inputTerms = input.value.toLowerCase();
            results = [];
            termsArray = inputTerms.split(' ');
            prefix = '';
            if(inputTerms === null) prefix = termsArray.length === 1 ? '' : termsArray.slice(0, -1).join(' ') + ' ';

            terms = termsArray[termsArray.length -1].toLowerCase();
            
            for (var i = 0; i < searchIndex.length; i++) {
                var a = searchIndex[i].toLowerCase(),
                    t = a.indexOf(terms);
                
                if (t > -1) {
                results.push(a);
                }
            }
            
            evaluateResults();
        };


        var evaluateResults = function() {
            if (results.length > 0 && inputTerms.length > 0 && terms.length !== 0) {
                sortedResults = results.sort(sortResults);
                appendResults();
            } 
            else if (inputTerms.length > 0 && terms.length !== 0) {
                ul.innerHTML = '<li>{{__('There are no matches!')}}</li>';
                
            }
            else if (inputTerms.length !== 0 && terms.length === 0) {
                return;
            }
            else {
                clearResults();
            }
        };

        var sortResults = function (a,b) {
            if (a.indexOf(terms) < b.indexOf(terms)) return -1;
            if (a.indexOf(terms) > b.indexOf(terms)) return 1;
            return 0;
        }

        var appendResults = function () {
            clearResults();
            
            for (var i=0; i < sortedResults.length && i < 5; i++) {
                var li = document.createElement("li"),
                    result = prefix 
                    + sortedResults[i].toLowerCase().replace(terms, '<strong>' 
                    + terms 
                    +'</strong>');
                
                li.innerHTML = result;
                ul.appendChild(li);
            }
            
            if ( ul.className !== "term-list") {
                ul.className = "term-list";
            }
        
        
        };

        let toCapitalize = function(sentence){
            const words = sentence.split(' ');
            const capitalizedWords = words.map(word => word.charAt(0).toUpperCase() + word.slice(1));
            const titleCaseSentence = capitalizedWords.join(' ');
            return titleCaseSentence;
        }

        let clearResults = function() {
            ul.className = "term-list hidden";
            ul.innerHTML = '';
            inputTerms = '';
        };
        
    
        input.addEventListener("keyup", search, false);
        ul.addEventListener('click', function(event) {
            event.stopPropagation();
            const clickedLi = event.target.closest('li');

            if (clickedLi) {
                input.value = toCapitalize(clickedLi.textContent);
                clearResults();
            }
        });

        function mergeStatesAndMunicipalities(data){
            const resultado = Object.entries(data).flatMap(([estado, ciudades]) =>
                ciudades.map(ciudad => `${estado} - ${ciudad}`)
            );
            console.log(resultado);
            return resultado;
        }

    </script>
@endsection