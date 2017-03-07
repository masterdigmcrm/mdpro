<form id="sForm" class="form-horizontal" method="GET">
    <div class="form-group">
        <label for="q" class="col-sm-3">Keyword</label>
        <div class="col-sm-9">
            <div class="form-group">
                <input type="text" name="q" value="" id="q" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3">Types</label>
        <div class="col-sm-9">
            <select name="typeid" class="form-control">
                <option value="0"> All </option>
                <option value="" v-for="t in property_types" :value="t.typeid"> {{t.type}} </option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6" >
            <label for="price">Min Price</label>
            <input type="text" name="min_price" value="" id="min_price" class="form-control" />
        </div>
        <div class="col-md-6">
            <label for="price">Max Price</label>
            <input type="text" name="max_price" value="" id="max_price" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6" >
            <label for="price">Beds</label>
            <select name="beds" class="form-control">
                <option value="0"> All </option>
                <option value="" v-for="n in 10" :value="n"> {{n}}+ </option>
            </select>
        </div>
        <div class="col-md-6" >
            <label for="baths">Bath</label>
            <select name="baths" class="form-control">
                <option value="0"> All </option>
                <option value="" v-for="n in 10" :value="n"> {{n}}+ </option>
            </select>
        </div>
    </div>
    <hr />
    <div class="form-group">
        <div class="col-md-6" >
            <a href="javascript:" class="btn btn-sm btn-primary" @click="search">Search</a>
        </div>
    </div>
    <div class="clearfix"></div>
    <input type="hidden" name="page" id="search_page" value="" v-model="page"/>
</form>

