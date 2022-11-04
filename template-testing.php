<?php
/**
* Template Name: Test template
*
*/

get_header();


$users = get_users( array( 'fields' => array( 'ID' ) ) );

// $_GET['search'];
$search = isset($_GET['search']) && $_GET['search'] !== '' ? $_GET['search'] : '';
$categories = isset($_GET['categories']) && $_GET['categories'] !== '' ? explode( ',', $_GET['categories'] ) : '';

// print_r($categories);
// exit;

$store_data = [];
$stores_data = [];
$allCategories = [];
foreach($users as $user){
    // user id
    $user_id = $user->ID;
    $store_info = dokan_get_store_info($user_id);
    if(!empty($store_info['store_name'])){

        $store_data['store_name'] = $store_info['store_name'];
        $store_data['banner_id'] = isset($store_info['banner']) ? $store_info['banner'] : 0;
        $store_data['gravatar'] = isset($store_info['gravatar']) ? absint($store_info['gravatar']) : 0;
        $store_data['address'] = $store_info['address'];
        $store_data['phone'] = $store_info['phone'];
        $store_data['user_id'] = $user_id;
        $store_data['categories'] = $store_info['categories'];

        if(!empty($store_info['categories'])){
            foreach($store_info['categories'] as $cat){

                if(!in_array($cat->slug ,$allCategories)){
                    $allCategories[$cat->slug] = $cat->name;
                }
            }
            
        }
        // echo "<pre>";
        // print_r($search);
       

        $stores_data[] = $store_data;
    }

   
}

$filter_data = [];
if($categories){
    foreach($categories as $cat){
        foreach($stores_data as $store){

            foreach($store['categories'] as $cats){
                if($cats->slug === $cat){
                    $filter_data[$store['user_id']] = $store;
                }
                
            }
        }
    }

    $stores_data = $filter_data;
}


$filter_data = [];
if($search !== ''){
    foreach($stores_data as $store){

        // echo "<pre>";
        // print_r( strpos( strtolower($store['store_name']), strtolower('') ) );
        // exit;


        if(strpos(strtolower($store['store_name']), strtolower($search)) !== FALSE){
            $filter_data[$store['user_id']] = $store;
        }      
    }

    $stores_data = $filter_data;
}


$store_counts = count($stores_data);

// echo "<pre>";
// print_r($search);


 ?> 

<style>

    .category-box{
        background: #fff;
        box-shadow: 1px 1px 20px 0 #bbb;
        padding: 20px 10px;
        width: 400px;
        margin-top: 10px;
        z-index: 500;
    }

    .category-box ul{
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -moz-flex;
        display: -webkit-flex;
        flex-wrap: wrap;
        -webkit-flex-wrap: wrap;
        justify-content: space-around;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .category-box ul li{
        border: 1px solid #ccc;
        border-radius: 3px;
        cursor: pointer;
        list-style: none;
        margin: 5px;
        padding: 5px 10px;
    }
    .store-lists-category .category-input{
        border: 1px solid #ccc;
        border-radius: 3px;
        color: #8c8a8a;
        cursor: pointer;
        padding: 10px 15px;
        position: relative;
        width: 250px;
    }
    .category-box ul li.selected{
        color: white;
        background-color: var(--wolmart-primary-color-hover,ligthen(#2879FE,10%));
    }
    .store-search-input{
    padding: 7px 20px;
    border-color: var(--wolmart-border-color-light,#eee);
    width: 100%;
    border-radius: 3px;
    border: 1px solid #cccccc;
    margin: unset;
    display: unset;
    text-transform: unset;
    font-weight: unset;
    line-height: unset;
    font-size: unset;
    letter-spacing: unset;
    box-shadow: unset;
    box-sizing: border-box;
    }
    .section {
        font-family: "Poppins", sans-serif;
        }
        .section .woodpartner-seller {
        margin: 0;
        padding:0;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            justify-content: center;
        }
        .section .woodpartner-seller br{
            display: contents;
        }
        .section .woodpartner-seller .card {
            border: 0.001px solid whitesmoke;
            width: 30%;
            border-radius: 5px;
            padding-bottom: 30px;
            margin: 15px;
            background: #F7F7F7;
            list-style: none;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.08);
        }
        .section .woodpartner-seller .card .top-part {
        position: relative;
        }
        .section .woodpartner-seller .card .top-part .background-img {
        width: 100%;
        -o-object-fit: cover;
            object-fit: cover;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }
        .section .woodpartner-seller .card .top-part svg {
            position: absolute;
            top: 20px;
            background: rgb(205, 23, 25);
            right: 24px;
            padding: 4px;
            border-radius: 50%;
            width: 30px;
            height: 30px;
        }
        .section .woodpartner-seller .card .bottom-part .sec-1 {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
            -ms-flex-align: center;
                align-items: center;
        position: relative;
        border-bottom: 1px solid #dcdcdc;

        }
        .section .woodpartner-seller .card .bottom-part .sec-1 .logo {
        margin: 18px 0 18px 18px;
        }
        .section .woodpartner-seller .card .bottom-part .sec-1 .heading {
        margin-left: 15px;
        }
        .section .woodpartner-seller .card .bottom-part .sec-1 .heading .h3 {
        color: #6F6F6F;
        font-size: 15px;
        font-weight: 600;
        }
        .section .woodpartner-seller .card .bottom-part .sec-1 .heading h2 {
        font-size: 20px;
        font-weight: 600;
        color: #141414;
        margin: 0;
        }
        .section .woodpartner-seller .card .bottom-part .sec-2 {
        margin: 18px 0px 0px 18px;
        }
        .section .woodpartner-seller .card .bottom-part .sec-2 .location a {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
            -ms-flex-align: center;
                align-items: center;
        text-decoration: none;
        }
        .section .woodpartner-seller .card .bottom-part .sec-2 .location a svg {
        margin-right: 10px;
        }
        .section .woodpartner-seller .card .bottom-part .sec-2 .location a span {
        color: black;
        }
        .section .woodpartner-seller .card .bottom-part .sec-2 .telephone {
        margin-top: 20px;
        margin-bottom: 30px;
        }
        .section .woodpartner-seller .card .bottom-part .sec-2 .telephone span{
        color: black;
        }
        .section .woodpartner-seller .card .bottom-part .sec-2 .store-btn .btn {
        border-radius: 8px;
        border: 1px solid #D6D6D6;
        background: white;
        padding: 12px 20px 12px 20px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        color: #8E8E8E;
        }
        .section .woodpartner-seller .card .bottom-part .sec-2 .store-btn .btn:hover {
        background: #CD1719;
        color: white !important;
        }
        .section .woodpartner-seller .card .bottom-part .sec-2 .store-btn .btn:hover svg path {
        fill: white;
        }

        .section .woodpartner-seller .card .bottom-part .sec-2 .store-btn .btn svg path {
        fill: #8E8E8E;
        }

        .section .woodpartner-seller .card .bottom-part .sec-2 .store-btn .btn{
            margin-right: 10px;
        }

        @media (max-width: 945px) {
        .card {
            width: 48% !important;
        }
        }
        @media (max-width: 656px) {
        .card {
            width: 100% !important;
        }
        }
</style>
<div class="filters container mb-5">
        <div class="left d-flex align-items-center justify-content-between mb-5">
            <h3 class="item store-count mb-0">Total store showing <?php echo $store_counts; ?></h3>
            <button id="filter_button" class="dokan-store-list-filter-button btn btn-outline btn-primary btn-icon-left">
                <i class="w-icon-category"></i>Filter
            </button>
        </div>
        <form class="d-none filter_form" style="padding: 2rem;border-radius: 5px;box-shadow: 0px 8px 20px rgba(0,0,0,0.08);background: #ffffff;margin-top: 32px;">

            <div class="store-search grid-item mb-5">
                <input type="search" class="store-search-input" name="dokan_seller_search" placeholder="Search Vendors">
            </div>

            <div class="store-lists-category item mb-5">
                <div class="category-input">
                    <span class="category-label">Category:</span>
                    <span class="category-items">construction, f...</span>
                    <span class="dokan-icon dashicons dashicons-arrow-down-alt2"></span>
                </div>

                <div class="category-box store_category d-none" style="">
                    <ul>
                        <?php
                        foreach($allCategories as $key => $cat){
                            ?>
                            <li data-slug="<?php echo $key; ?>"><?php echo $cat; ?></li>   
                            <?php
                        }
                        ?>      
                    </ul>
                </div>
            </div>


            <div class="apply-filter">
                <button id="apply-filter-btn" class="btn btn-primary btn-rounded font-weight-semi-bold text-uppercase" type="submit">Apply</button>
            </div>

        </form>
    </div>
<div class="section container">
    <ul class="woodpartner-seller">
        <?php

            foreach($stores_data as $info){

                    $store_name = $info['store_name'];
                    $banner_id = $info['banner_id'];
                    $banner_url = wp_get_attachment_image_src($banner_id, array('427', '238'));
                    $banner_url = $banner_url[0] ? esc_url($banner_url[0]) : dokan_get_no_seller_image();

                    $gravatar = $info['gravatar'];
                    $gravatar_url = $gravatar ? wp_get_attachment_url($gravatar) : '';

                    $country = $info['address']['country'];

                    switch ($country) {
                        case 'FR':
                            $country = 'France';
                            break;
                        case 'BE':
                            $country = 'Belgium';
                            break;
                        case 'DE':
                            $country = 'Germany';
                            break;
                        case 'IT':
                            $country = 'Italy';
                            break;
                        case 'CA':
                            $country = 'Canada';
                            break;
                        case 'ES':
                            $country = 'Spain';
                            break;
                        case 'AT':
                            $country = 'Austria';
                            break;
                            default:
                            $country = $country;
                    }

                    $address = $info['address']['street_1'] . ", " . $info['address']['city'] . ", " . $country;
                    ?>
                    <li class="card">
                        <div class="top-part">
                        <img style="max-height: 238px; height: 100%;" class="background-img" src="<?php echo $banner_url; ?>" alt="">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_6_71)">
                            <path d="M16.5 3C19.538 3 22 5.5 22 9C22 16 14.5 20 12 21.5C9.5 20 2 16 2 9C2 5.5 4.5 3 7.5 3C9.36 3 11 4 12 5C13 4 14.64 3 16.5 3Z" fill="white"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_6_71">
                            <rect width="24" height="24" fill="white"/>
                            </clipPath>
                            </defs>
                            </svg>              
                        </div>

                        <div class="bottom-part">
                        <div class="sec-1">
                            <div class="logo">
                            <img style="width: 71px; height: 71px; border-radius: 50%;" src="<?php echo $gravatar_url; ?>" alt="">
                            </div>
                            <div class="heading">
                            <div class="h3"><?php echo $country; ?></div>
                            <H2><?php echo $store_name; ?></H2>
                            </div>
                        </div>

                        <div class="sec-2">
                            <div class="location">
                            <a href="#">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_6_17)">
                                <path d="M18.364 17.364L12 23.728L5.636 17.364C4.37734 16.1053 3.52019 14.5017 3.17293 12.7559C2.82567 11.01 3.00391 9.20044 3.6851 7.55591C4.36629 5.91139 5.51984 4.50579 6.99988 3.51686C8.47992 2.52793 10.22 2.00009 12 2.00009C13.78 2.00009 15.5201 2.52793 17.0001 3.51686C18.4802 4.50579 19.6337 5.91139 20.3149 7.55591C20.9961 9.20044 21.1743 11.01 20.8271 12.7559C20.4798 14.5017 19.6227 16.1053 18.364 17.364V17.364ZM12 15C13.0609 15 14.0783 14.5786 14.8284 13.8284C15.5786 13.0783 16 12.0609 16 11C16 9.93915 15.5786 8.92173 14.8284 8.17159C14.0783 7.42144 13.0609 7.00001 12 7.00001C10.9391 7.00001 9.92172 7.42144 9.17158 8.17159C8.42143 8.92173 8 9.93915 8 11C8 12.0609 8.42143 13.0783 9.17158 13.8284C9.92172 14.5786 10.9391 15 12 15V15ZM12 13C11.4696 13 10.9609 12.7893 10.5858 12.4142C10.2107 12.0392 10 11.5304 10 11C10 10.4696 10.2107 9.96087 10.5858 9.5858C10.9609 9.21073 11.4696 9.00001 12 9.00001C12.5304 9.00001 13.0391 9.21073 13.4142 9.5858C13.7893 9.96087 14 10.4696 14 11C14 11.5304 13.7893 12.0392 13.4142 12.4142C13.0391 12.7893 12.5304 13 12 13Z" fill="#CD1719"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_6_17">
                                <rect width="24" height="24" fill="white"/>
                                </clipPath>
                                </defs>
                            </svg>                  
                            <span>
                                 <?php
                                    if (!empty($info['address'])) {
                                        echo $address;
                                    }
                                ?>
                            </span> 
                            </a>
                            </div>
                            <div class="telephone">
                            <a href="tel:<?php echo $info['phone']; ?>">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_6_27)">
                                    <path d="M21 16.42V19.956C21.0001 20.2092 20.9042 20.453 20.7316 20.6382C20.559 20.8234 20.3226 20.9363 20.07 20.954C19.633 20.984 19.276 21 19 21C10.163 21 3 13.837 3 5C3 4.724 3.015 4.367 3.046 3.93C3.06372 3.67744 3.17658 3.44101 3.3618 3.26841C3.54703 3.09581 3.79082 2.99989 4.044 3H7.58C7.70404 2.99987 7.8237 3.04586 7.91573 3.12902C8.00776 3.21218 8.0656 3.32658 8.078 3.45C8.101 3.68 8.122 3.863 8.142 4.002C8.34073 5.38892 8.748 6.73783 9.35 8.003C9.445 8.203 9.383 8.442 9.203 8.57L7.045 10.112C8.36445 13.1865 10.8145 15.6365 13.889 16.956L15.429 14.802C15.4919 14.714 15.5838 14.6509 15.6885 14.6237C15.7932 14.5964 15.9042 14.6068 16.002 14.653C17.267 15.2539 18.6156 15.6601 20.002 15.858C20.141 15.878 20.324 15.9 20.552 15.922C20.6752 15.9346 20.7894 15.9926 20.8724 16.0846C20.9553 16.1766 21.0012 16.2961 21.001 16.42H21Z" fill="#CD1719"/>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_6_27">
                                    <rect width="24" height="24" fill="white"/>
                                    </clipPath>
                                    </defs>
                                </svg>

                                <span><?php echo $info['phone']; ?></span>
                            </a>
                            </div>
                            <div class="store-btn">
                            <a class="btn" href="<?php echo dokan_get_store_url($info['user_id']); ?>">
                            <span>VISIT STORE</span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_6_32)">
                                <path d="M16.172 11L10.808 5.63598L12.222 4.22198L20 12L12.222 19.778L10.808 18.364L16.172 13H4V11H16.172Z" fill="white"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_6_32">
                                <rect width="24" height="24" fill="white"/>
                                </clipPath>
                                </defs>
                            </svg> 
                            </a>
                            </div>
                        </div>
                        </div>


                    </li>
                    <?php


       
            }

        ?>
    </ul>
</div>
<script>
    function toggleFilters(event){
        document.querySelector(".filter_form").classList.toggle("d-none");
    }
    function toggleCategory(event){
        document.querySelector(".category-box").classList.toggle("d-none");
        document.querySelector(".category-input .dashicons").classList.toggle("dashicons-arrow-down-alt2");
        document.querySelector(".category-input .dashicons").classList.toggle("dashicons-arrow-up-alt2");
    }
    function toggleCategorySelect(event){
        event.currentTarget.classList.toggle("selected");

    }
    function applyFilters(event){
        var store_category = [];
        var storeSearchInput = document.querySelector(".store-search-input").value;
        event.preventDefault();
        document.querySelectorAll(".category-box ul li.selected").forEach(item => {
           store_category.push(item.getAttribute("data-slug"));
        });

        var arrStr = store_category !==[] ? encodeURIComponent(store_category) : '';

        window.location.href = window.location.hostname + window.location.pathname + "?categories=" + arrStr + "&search=" +storeSearchInput ;
    }


    
    document.querySelector("#filter_button").addEventListener("click", toggleFilters);
    document.querySelector(".category-input").addEventListener("click", toggleCategory);
    document.querySelectorAll(".category-box ul li").forEach(item => {
        item.addEventListener("click", toggleCategorySelect);
    });
    document.querySelector("#apply-filter-btn").addEventListener("click", applyFilters);
</script>
<?php get_footer() ?>