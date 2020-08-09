@extends('layouts.main')
@section('title', 'Uniranks | Listing Page Development')  <?php // TODO: add dynamic title here ?>

@section('content')
  <img class="banner w-100" src={{ asset("assets/img/banner.jpg") }} />
  <div class="d-flex px-5 py-4">
    <div class="flex-fill mr-4">
      <img class="d-block m-auto" src={{ asset("assets/img/ad-horizontal.jpg") }} />
      <ul class="nav nav-tabs mt-4" id="listingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true"><span class="nav_link_border">World</span></a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false"><span class="nav_link_border">Tab2</span></a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab3-tab" data-toggle="tab" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false"><span class="nav_link_border">Tab3</span></a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab4-tab" data-toggle="tab" href="#tab4" role="tab" aria-controls="tab4" aria-selected="false"><span class="nav_link_border">Tab4</span></a>
        </li>
      </ul>
      <div class="card d-flex flex-row">
        <div class="ranking_factors_title p-1 d-flex flex-fill">
          <p class="factor_title flex-fill align-self-center">Rank</p>
          <div class="factor_arrows d-flex flex-column align-self-center">
            <i class="fas fa-sort-up"></i>
            <i class="fas fa-sort-down"></i>
          </div>
        </div>
        <div class="ranking_factors_title p-1 d-flex flex-fill">
          <p class="factor_title flex-fill align-self-center">Country</p>
          <div class="factor_arrows d-flex flex-column align-self-center">
            <i class="fas fa-sort-up"></i>
            <i class="fas fa-sort-down"></i>
          </div>
        </div>
        <div class="ranking_factors_title p-1 d-flex flex-fill">
          <p class="factor_title flex-fill align-self-center">Web</p>
          <div class="factor_arrows d-flex flex-column align-self-center">
            <i class="fas fa-sort-up"></i>
            <i class="fas fa-sort-down"></i>
          </div>
        </div>
        <div class="ranking_factors_title p-1 d-flex flex-fill">
          <p class="factor_title flex-fill align-self-center">Wet</p>
          <div class="factor_arrows d-flex flex-column align-self-center">
            <i class="fas fa-sort-up"></i>
            <i class="fas fa-sort-down"></i>
          </div>
        </div>
        <div class="ranking_factors_title p-1 d-flex flex-fill">
          <p class="factor_title flex-fill align-self-center">Res</p>
          <div class="factor_arrows d-flex flex-column align-self-center">
            <i class="fas fa-sort-up"></i>
            <i class="fas fa-sort-down"></i>
          </div>
        </div>
        <div class="ranking_factors_title p-1 d-flex flex-fill">
          <p class="factor_title flex-fill align-self-center">Qu</p>
          <div class="factor_arrows d-flex flex-column align-self-center">
            <i class="fas fa-sort-up"></i>
            <i class="fas fa-sort-down"></i>
          </div>
        </div>
        <div class="ranking_factors_title p-1 d-flex flex-fill">
          <p class="factor_title flex-fill align-self-center">Edu</p>
          <div class="factor_arrows d-flex flex-column align-self-center">
            <i class="fas fa-sort-up"></i>
            <i class="fas fa-sort-down"></i>
          </div>
        </div>
        <div class="ranking_factors_title p-1 d-flex flex-fill">
          <p class="factor_title flex-fill align-self-center">Acad</p>
          <div class="factor_arrows d-flex flex-column align-self-center">
            <i class="fas fa-sort-up"></i>
            <i class="fas fa-sort-down"></i>
          </div>
        </div>
        <div class="ranking_factors_title p-1 d-flex flex-fill">
          <p class="factor_title flex-fill align-self-center">Qty</p>
          <div class="factor_arrows d-flex flex-column align-self-center">
            <i class="fas fa-sort-up"></i>
            <i class="fas fa-sort-down"></i>
          </div>
        </div>
        <div class="ranking_factors_title p-1 d-flex flex-fill">
          <p class="factor_title flex-fill align-self-center">Int</p>
          <div class="factor_arrows d-flex flex-column align-self-center">
            <i class="fas fa-sort-up"></i>
            <i class="fas fa-sort-down"></i>
          </div>
        </div>
        <div class="ranking_factors_title p-1 d-flex flex-fill">
          <p class="factor_title flex-fill align-self-center">UR Rating</p>
          <div class="align-self-center">
            <input class="listings_checkbox" type="checkbox" />
          </div>
        </div>
      </div>
      <div class="card p-2 card_container">
        <div class="tab-content" id="listingsTabsContent">
          <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
            <div class="card listing_item mb-2 p-2 d-flex flex-row">
              <div class="align-self-center pr-4 pl-3">
                <img class="university_listing_logo" src={{ asset("assets/img/placeholder.jpeg") }} />
              </div>
              <div class="ranking_area pl-2 d-flex flex-fill flex-column">
                <div class="d-flex mb-2 mt-0">
                  <h1 class="university_name flex-fill">University of Oxford</h1>
                  <div class="d-flex justify-content-between">
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                  </div>
                </div>
                <div class="d-flex">
                  <h3 class="university_listing_address flex-fill"><i class="fas fa-map-marker-alt"></i> Oxford OX1 2JD, United Kingdom</h3>
                  <span class="badge badge-pill badge-secondary align-self-start">North America (1)</span>
                </div>
                <div class="d-flex mt-2">
                  <div class="p-1 listing_graph_area flex-fill mr-2">
                    listing_graph_area
                  </div>
                  <button class="btn btn-secondary mr-2">Compare</button>
                  <button class="btn btn-primary">Explore</button>
                </div>
              </div>
            </div>
            <div class="card listing_item mb-2 p-2 d-flex flex-row">
              <div class="align-self-center pr-4 pl-3">
                <img class="university_listing_logo" src={{ asset("assets/img/placeholder.jpeg") }} />
              </div>
              <div class="ranking_area pl-2 d-flex flex-fill flex-column">
                <div class="d-flex mb-2 mt-0">
                  <h1 class="university_name flex-fill">University of Oxford</h1>
                  <div class="d-flex justify-content-between">
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                    <div class="ranking_factors_value align-self-center p-1">
                      <p class="factor_value">100</p>
                    </div>
                  </div>
                </div>
                <div class="d-flex">
                  <h3 class="university_listing_address flex-fill"><i class="fas fa-map-marker-alt"></i> Oxford OX1 2JD, United Kingdom</h3>
                  <span class="badge badge-pill badge-secondary align-self-start">North America (1)</span>
                </div>
                <div class="d-flex mt-2">
                  <div class="p-1 listing_graph_area flex-fill mr-2">
                    listing_graph_area
                  </div>
                  <button class="btn btn-secondary mr-2">Compare</button>
                  <button class="btn btn-primary">Explore</button>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
            test content2
          </div>
          <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
            test content3
          </div>
          <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab4-tab">
            test content4
          </div>
        </div>
      </div>
    </div>
    <div class="sidebar_content d-flex flex-column">
      <button class="btn btn-primary mb-2 shadow-sm">Suggest Similar</button>
      <button class="btn btn-primary mb-2 shadow-sm">Search</button>
      <button class="btn btn-secondary mb-2 shadow-sm">One Click Application</button>
      <ul class="nav nav-tabs mt-3" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-toggle="tab" href="#quick_links" role="tab" aria-selected="true"><span class="nav_link_border text-uppercase">quick links</span></a>
        </li>
      </ul>
      <div class="card card_container">
        <div class="tab-content">
          <div class="tab-pane fade show active d-flex flex-column" id="quick_links" role="tabpanel">
            <a class="p-2 sidebar_link d-flex" href="#">
              <p class="flex-fill">Undergraduate</p>
              <i class="fas fa-angle-right"></i>
            </a>
            <a class="p-2 sidebar_link d-flex" href="#">
              <p class="flex-fill">Postgraduate</p>
              <i class="fas fa-angle-right"></i>
            </a>
            <a class="p-2 sidebar_link d-flex" href="#">
              <p class="flex-fill">Where to Study</p>
              <i class="fas fa-angle-right"></i>
            </a>
            <a class="p-2 sidebar_link d-flex" href="#">
              <p class="flex-fill">What to Study</p>
              <i class="fas fa-angle-right"></i>
            </a>
            <a class="p-2 sidebar_link d-flex" href="#">
              <p class="flex-fill">Meet Schools</p>
              <i class="fas fa-angle-right"></i>
            </a>
            <a class="p-2 sidebar_link d-flex" href="#">
              <p class="flex-fill">Methodology</p>
              <i class="fas fa-angle-right"></i>
            </a>
          </div>
        </div>
      </div>
      <ul class="nav nav-tabs mt-4" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-toggle="tab" href="#top_search" role="tab" aria-selected="true"><span class="nav_link_border text-uppercase">students top search</span></a>
        </li>
      </ul>
      <div class="card card_container">
        <div class="tab-content">
          <div class="tab-pane fade show active d-flex flex-column" id="top_search" role="tabpanel">
            <a class="p-2 sidebar_link d-flex" href="#">
              <p class="flex-fill">Undergraduate</p>
              <i class="fas fa-angle-right"></i>
            </a>
            <a class="p-2 sidebar_link d-flex" href="#">
              <p class="flex-fill">Postgraduate</p>
              <i class="fas fa-angle-right"></i>
            </a>
            <a class="p-2 sidebar_link d-flex" href="#">
              <p class="flex-fill">Where to Study</p>
              <i class="fas fa-angle-right"></i>
            </a>
            <a class="p-2 sidebar_link d-flex" href="#">
              <p class="flex-fill">What to Study</p>
              <i class="fas fa-angle-right"></i>
            </a>
            <a class="p-2 sidebar_link d-flex" href="#">
              <p class="flex-fill">Meet Schools</p>
              <i class="fas fa-angle-right"></i>
            </a>
            <a class="p-2 sidebar_link d-flex" href="#">
              <p class="flex-fill">Methodology</p>
              <i class="fas fa-angle-right"></i>
            </a>
          </div>
        </div>
      </div>
      <img class="d-block m-auto pt-4" src={{ asset("assets/img/ad-vertical.jpg") }} />
      <ul class="nav nav-tabs mt-4" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-toggle="tab" href="#sidebar_events" role="tab" aria-selected="true"><span class="nav_link_border text-uppercase">upcoming events</span></a>
        </li>
      </ul>
      <div class="card card_container">
        <div class="tab-content">
          <div class="tab-pane fade show active d-flex flex-column" id="sidebar_events" role="tabpanel">
            <div class="p-2 d-flex event_container">
              <img class="event_image rounded-circle" src={{ asset("assets/img/placeholder.jpeg") }} />
              <div class="d-flex flex-column">
                <p class="event_date">Friday, 24th June</p>
                <p class="event_desc">Lorem ipsum is simply dummy text...</p>
                <a class="event_link" href="#">Register Now</a>
              </div>
            </div>
            <div class="p-2 d-flex event_container">
              <img class="event_image rounded-circle" src={{ asset("assets/img/placeholder.jpeg") }} />
              <div class="d-flex flex-column">
                <p class="event_date">Friday, 24th June</p>
                <p class="event_desc">Lorem ipsum is simply dummy text...</p>
                <a class="event_link" href="#">Register Now</a>
              </div>
            </div>
            <div class="p-2 d-flex event_container">
              <img class="event_image rounded-circle" src={{ asset("assets/img/placeholder.jpeg") }} />
              <div class="d-flex flex-column">
                <p class="event_date">Friday, 24th June</p>
                <p class="event_desc">Lorem ipsum is simply dummy text...</p>
                <a class="event_link" href="#">Register Now</a>
              </div>
            </div>
            <a class="p-2 sidebar_link view_events d-flex" href="#">
              <p class="flex-fill">View All</p>
              <i class="fas fa-angle-right"></i>
            </a>
          </div>
        </div>
      </div>
      <ul class="nav nav-tabs mt-4" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-toggle="tab" href="#featured_universities" role="tab" aria-selected="true"><span class="nav_link_border text-uppercase">featured universities</span></a>
        </li>
      </ul>
      <div class="card card_container">
        <div class="tab-content">
          <div class="tab-pane fade show active d-flex flex-column" id="featured_universities" role="tabpanel">
            <div class="d-flex flex-column featured_university_container">
              <p class="featured_university_name">University of Oxford</p>
              <img class="featured_university_cover my-2 w-full" src={{ asset("assets/img/placeholder.png") }} />
              <div class="d-flex">
                <img class="featured_university_logo shadow-sm" src={{ asset("assets/img/placeholder.jpeg") }} />
                <a class="btn btn-sm shadow-sm btn-secondary mr-2 flex-fill" href="#"><i class="fas fa-play small_play_icon"></i> Video</a>
                <a class="btn btn-sm shadow-sm btn-primary mr-2 flex-fill" href="#">Explore</a>
              </div>
            </div>
            <div class="d-flex flex-column featured_university_container">
              <p class="featured_university_name">University of Oxford</p>
              <img class="featured_university_cover my-2 w-full" src={{ asset("assets/img/placeholder.png") }} />
              <div class="d-flex">
                <img class="featured_university_logo shadow-sm" src={{ asset("assets/img/placeholder.jpeg") }} />
                <a class="btn btn-sm shadow-sm btn-secondary mr-2 flex-fill" href="#"><i class="fas fa-play small_play_icon"></i> Video</a>
                <a class="btn btn-sm shadow-sm btn-primary mr-2 flex-fill" href="#">Explore</a>
              </div>
            </div>
            <div class="d-flex flex-column featured_university_container">
              <p class="featured_university_name">University of Oxford</p>
              <img class="featured_university_cover my-2 w-full" src={{ asset("assets/img/placeholder.png") }} />
              <div class="d-flex">
                <img class="featured_university_logo shadow-sm" src={{ asset("assets/img/placeholder.jpeg") }} />
                <a class="btn btn-sm shadow-sm btn-secondary mr-2 flex-fill" href="#"><i class="fas fa-play small_play_icon"></i> Video</a>
                <a class="btn btn-sm shadow-sm btn-primary mr-2 flex-fill" href="#">Explore</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <section class="news px-5 pt-4">
    <div class="light_gray_background">
      <img class="d-block m-auto" src={{ asset("assets/img/ad-horizontal.jpg") }} />
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <img class="card-img-top" src={{ asset("assets/img/placeholder.jpeg") }} />
          <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a class="event_link" href="#">Go somewhere</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <img class="card-img-top" src={{ asset("assets/img/placeholder.jpeg") }} />
          <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a class="event_link" href="#">Go somewhere</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <img class="card-img-top" src={{ asset("assets/img/placeholder.jpeg") }} />
          <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a class="event_link" href="#">Go somewhere</a>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection
