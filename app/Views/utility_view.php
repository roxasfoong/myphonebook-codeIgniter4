
<!-- 

    HTML Element ID & Usage
    ID   : fAlphabet
    Usage: Select a alphabet to filter from dropdown list 

    ID   : sortingMethod
    Usage: Select a sorting method from dropdown list 

    ID   : searchInput
    Usage: Allow user to enter text used for search contact by name

    ID   : searchButton
    Usage: After press the button then the the value of HTML:searchInput will be sent to server to process

-->


<div class="container mb-5">
    <div class="card text-center add-shadow-2">
        <div class="card-header bg-dark text-white">
            <div class="container">
            <div class="row">
                <div class="col-sm-6 col-12 m-auto">
              
            <div class="input-group">
                <input type="text" class="form-control" id="searchInput" placeholder="Search Contact by Name...">
                <div class="input-group-append">
                    <button id="searchButton" class="btn btn-danger" type="button">Search</button>
                </div>
            </div>
                
                </div>
            </div>
            </div>

        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-6 m-auto">
                    <div class="card mb-3">
                        <div class="card-body border-line-1">
                            <div class="col-sm-6 col-12 m-auto"> 
                        
                            <select class="form-control text-center border-line-1" id="fAlphabet">
                                <option value="1">All</option>
                                <option value="a">A</option>
                                <option value="b">B</option>
                                <option value="c">C</option>
                                <option value="d">D</option>
                                <option value="e">E</option>
                                <option value="f">F</option>
                                <option value="g">G</option>
                                <option value="h">H</option>
                                <option value="i">I</option>
                                <option value="j">J</option>
                                <option value="k">K</option>
                                <option value="l">L</option>
                                <option value="m">M</option>
                                <option value="n">N</option>
                                <option value="o">O</option>
                                <option value="p">P</option>
                                <option value="q">Q</option>
                                <option value="r">R</option>
                                <option value="s">S</option>
                                <option value="t">T</option>
                                <option value="u">U</option>
                                <option value="v">V</option>
                                <option value="w">W</option>
                                <option value="x">X</option>
                                <option value="y">Y</option>
                                <option value="z">Z</option>
                            </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 m-auto">
                    <div class="card mb-3">
                        <div class="card-body border-line-1">
                            <div class="col-sm-6 col-12 m-auto">  
                        
            <select class="form-control text-center border-line-1" id="sortingMethod">
                <option value = "1">Ascending</option>
                <option value = "2">Descending</option>
                <option value = "3">New to Old</option>
                <option value = "4">Old to New</option>
            </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>