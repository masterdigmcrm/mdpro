<div style="float:right;padding-right:32px">
    <div> {{offsetValue}} to {{offsetTo}} of {{ total }} </div>
</div>
<ul class="pagination" v-show="page_count.length == 1 ? false : true ">

    <li v-show=" current_page > 1 ? true : false "><a href="javascript:"  @click="goToPrev" data-page="">Previous</a></li>
    <li v-for="pc in page_count" :class="current_page == pc ? 'active': '' ">
        <a href="javascript:" @click="goToPage" data-page="" :data-page="pc">{{pc}}</a>
    </li>
    <li v-show=" current_page < page_count.length ? true : false "><a href="javascript:" @click="goToNext" >Next</a></li>

</ul>
