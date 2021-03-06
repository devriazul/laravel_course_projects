@extends('layouts.frontend_app')

@section('front_title','All Posts')

@section('frontend_app_content')
<div class="container mt-lg-5">
    <div class="row mt-3">
      <!-- Latest Posts -->
      <main class="posts-listing col-lg-8">
        <div class="container">
          <div class="row">
            <!-- post -->
            {{-- @php
                print_r($posts->toArray());
            @endphp --}}

            @foreach ($posts as $post)
            <div class="post col-xl-6">
                <div class="post-thumbnail"><a href="{{ route('single-post',$post->slug) }}"><img src="{{ asset($post->image) }}" alt="{{ $post->name }}" class="img-fluid"></a></div>
                <div class="post-details">
                  <div class="post-meta d-flex justify-content-between">

                    <div class="date meta-last">{{ $post->created_at->format('D M | Y') }}</div>
                    <div class="category"><a href="{{ route('single-post',$post->slug) }}">{{ $post->category->name }}</a></div>
                  </div><a href="{{ route('single-post',$post->slug) }}">
                    <h3 class="h4">{{ $post->name }}</h3></a>
                  <p class="text-muted">{{ $post->short_des }}</p>
                  <footer class="post-footer d-flex align-items-center"><a href="#" class="author d-flex align-items-center flex-wrap">
                      <div class="avatar"><img src="{{ asset($post->author->image) }}" alt="..." class="img-fluid"></div>
                      <div class="title"><span>{{ $post->author->name }}</span></div></a>
                    <div class="date"><i class="icon-clock"></i> {{ $post->created_at->diffForHumans() }}</div>
                    <div class="comments meta-last"><i class="icon-comment"></i>12</div>
                  </footer>
                </div>
              </div>
            @endforeach

          </div>
          <div class="text-center" style="width: 50%;margin: 0 auto">
            {{ $posts->links('vendor.pagination.custom') }}
          </div>
          <!-- Pagination -->
          {{-- <nav aria-label="Page navigation example">
            <ul class="pagination pagination-template d-flex justify-content-center">
              <li class="page-item"><a href="#" class="page-link"> <i class="fa fa-angle-left"></i></a></li>
              <li class="page-item"><a href="#" class="page-link active">1</a></li>
              <li class="page-item"><a href="#" class="page-link">2</a></li>
              <li class="page-item"><a href="#" class="page-link">3</a></li>
              <li class="page-item"><a href="#" class="page-link"> <i class="fa fa-angle-right"></i></a></li>
            </ul>
          </nav> --}}
        </div>
      </main>
      <aside class="col-lg-4">
        <!-- Widget [Search Bar Widget]-->
        <div class="widget search">
          <header>
            <h3 class="h6">Search the blog <span class="badge badge-success" id="postCount"></span></h3>
          </header>
          <form action="#" class="search-form">
            <div class="form-group">
              <input type="search" placeholder="What are you looking for?" id="searchInput">
              <button type="submit" class="submit"><i class="icon-search"></i></button>
            </div>
          </form>
          <img src="{{ asset('loader.png') }}" alt="" id="loader">
          <div id="searchData">
              <ul>
                  {{-- <li><a href="">Lorm5 </a></li> --}}
              </ul>
          </div>
        </div>
        <!-- Widget [Latest Posts Widget]        -->
        <x-partial.blog-sidebar :tags="$tags" :categories="$categories" :latestPosts="$latestPosts" />
      </aside>
    </div>
  </div>
@stop

@push('script')
    {{-- <script>
        let searchInput = select('#searchInput');
        searchInput.addEventListener('keyup',async function(e) {
            let query = e.target.value;
            let url = `${window.location.origin}/search-post/${query}`;
            if(searchInput.value){
                const {data} = await axios.get(url)
                showPostData(data)
            }
        })
        const showPostData = (posts) => {
            let searchData = select('#searchData > ul');
            let li ;
            if(Object.keys(posts).length === 0) {
                li = `<li style="list-style:none;text-align:center;background:#ccc" class="p-2 text-danger">No Post Found!!</li>`;
            }else{
            li = posts.map(post => {
                    return `<li><a href="${window.location.origin}/single-post/${post.slug}">${post.name} </a> | ${post.author.name}</li>`;
                });
                li = li.join(" ");
            }
            searchData.innerHTML = li
        }
    </script> --}}

    <script>
        let searchInput = select('#searchInput');
        let loader = select('#loader');
        loader.style.display = 'none';
        searchInput.addEventListener('keyup',async function(e){
            let query = e.target.value;
            let url = `${window.location.origin}/search-post/${query}`;

            if(searchInput.value){
                // axios.get(url)
                // .then(res => {
                //     log(res)
                // }).catch(err => {
                //     console.log(res);
                // })

                try{
                    loader.style.display = 'block';
                    // let response = await axios.get(url);
                    let {data:posts} = await axios.get(url);
                    displayPost(posts);
                }catch(err){
                    loader.style.display = 'none';
                    log(err)
                }finally{
                    loader.style.display = 'none';
                }
            }
        });

        const displayPost = (posts) => {
            let postCount = select('#postCount');
            postCount.innerHTML = Object.keys(posts).length;
            let searchData = select('#searchData > ul');
            let li = null ;
            if(Object.keys(posts).length === 0){
                li = `<li style="list-style:none;text-align:center;background:#ccc" class="p-2 text-danger">No Post Found!!</li>`;
            }else{
                li = posts.map(post => {
                    return `<li><a href="${base_url}/single-post/${post.slug}">${post.name} | ${post.author.name}</a></li>`;
                });
                li = li.join(" ")
            }


            searchData.innerHTML = li;
        }
    </script>
@endpush
