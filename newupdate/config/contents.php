<?php
return [
    'banner_section_one' => [
        'single' => [
            'field_name' => [
                'title_part_one' => 'text',
                'title_part_two' => 'text',
                'title_part_three' => 'text',
                'search_text_one' => 'text',
                'search_text_two' => 'text',
                'search_text_three' => 'text',
                'search_button' => 'text',
                'button_name' => 'text',
                'background_image' => 'file',
            ],
            'validation' => [
                'title_part_one.*' => 'required|max:100',
                'title_part_two.*' => 'required|max:100',
                'title_part_three.*' => 'required|max:100',
                'search_text_one.*' => 'required|max:700',
                'search_text_two.*' => 'required|max:700',
                'search_text_three.*' => 'required|max:700',
                'search_button.*' => 'required|max:700',
                'button_name.*' => 'required|max:400',
                'background_image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
            ]
        ],
        'preview' => [
            'Homely Theme Banner One Image' => 'assets/preview/homely/banner_section_one.png'
        ],
        'theme' => 'homely'
    ],
    'banner_section_two' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'sub_heading_one' => 'text',
                'sub_heading_two' => 'text',
                'sub_heading_three' => 'text',
                'button_text' => 'text',
                'destination_button_text' => 'text',
                'search_text_one' => 'text',
                'search_text_two' => 'text',
                'search_text_three' => 'text',
                'search_button' => 'text',
                'video_link' => 'url',
                'image' => 'file',
            ],
            'validation' => [
                'heading.*' => 'required|max:200',
                'sub_heading_one.*' => 'required|max:200',
                'sub_heading_two.*' => 'required|max:200',
                'sub_heading_three.*' => 'required|max:200',
                'button_text.*' => 'required|max:700',
                'destination_button_text.*' => 'required|max:700',
                'search_text_one.*' => 'required|max:700',
                'search_text_two.*' => 'required|max:700',
                'search_text_three.*' => 'required|max:700',
                'search_button.*' => 'required|max:700',
                'video_link.*' => 'required|max:700',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
            ]
        ],
        'preview' => [
            'Homely Theme Banner Two Image' => 'assets/preview/homely/banner_section_two.png'
        ],
        'theme' => 'homely'
    ],
    'banner_section_three' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'sub_heading' => 'text',
                'button_text' => 'text',
                'destination_button_text' => 'text',
                'search_text_one' => 'text',
                'search_text_two' => 'text',
                'search_text_three' => 'text',
                'search_button' => 'text',
                'circle_text' => 'text',
                'circle_link' => 'url',
                'video_link' => 'url',
                'video_file' => 'video',
            ],
            'validation' => [
                'heading.*' => 'required|max:200',
                'sub_heading.*' => 'required|max:200',
                'button_text.*' => 'required|max:700',
                'destination_button_text.*' => 'required|max:700',
                'search_text_one.*' => 'required|max:700',
                'search_text_two.*' => 'required|max:700',
                'search_text_three.*' => 'required|max:700',
                'search_button.*' => 'required|max:700',
                'circle_text.*' => 'required|max:700',
                'circle_link.*' => 'required|max:700',
                'video_link.*' => 'required|max:700',
                'video_file.*' => 'nullable|file|max:102400|mimes:mp4,mov,avi,wmv,mkv',
            ]
        ],
        'preview' => [
            'Homely Theme Banner Three Image' => 'assets/preview/homely/banner_section_three.png'
        ],
        'theme' => 'homely'
    ],
    'top_rated_destination_section' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'sub_heading' => 'text',
                'video_link' => 'url',
                'video_file' => 'video',
            ],
            'validation' => [
                'heading.*' => 'required|max:200',
                'sub_heading.*' => 'required|max:200',
                'video_link.*' => 'required|max:700',
                'video_file.*' => 'nullable|file|max:102400|mimes:mp4,mov,avi,wmv,mkv',
            ]
        ],
        'preview' => [
            'Homely Theme Top Rated Destination Image' => 'assets/preview/homely/top_rated_destination_section.png'
        ],
        'theme' => 'homely'
    ],
    'category_section' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'description' => 'text',
                'button_name' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
                'description.*' => 'required|max:1000',
                'button_name.*' => 'required|max:100',
            ]
        ],
        'preview' =>[
            'Homely Theme Category Image' => 'assets/preview/homely/category_section.png'
        ],
        'theme' => 'homely',
    ],
    'feature_section' => [
        'single' => [
            'field_name' => [
                'image' => 'file',
            ],
            'validation' => [
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
            ]
        ],
        'multiple' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:400',
                'sub_title.*' => 'required|max:700',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
            ]
        ],
        'preview' =>[
            'Homely Theme Feature Two Image' => 'assets/preview/homely/feature.png'
        ],
        'theme' => 'homely',
    ],
    'feature_section2' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:400',
                'sub_title.*' => 'required|max:700',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
            ]
        ],
        'preview' =>[
            'Homely Theme Feature Two Image' => 'assets/preview/homely/feature_section2.png'
        ],
        'theme' => 'homely',
    ],
    'popular_amenities_seciton' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
            ]
        ],
        'preview' =>[
            'Homely Theme Popular Amenities Image' => 'assets/preview/homely/popular_amenities_seciton.png'
        ],
        'theme' => 'homely',
    ],
    'private_two' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
                'sub_title.*' => 'required|max:1000',
            ]
        ],
        'preview' =>[
            'Homely Theme Image' => 'assets/preview/homely/private_two.png'
        ],
        'theme' => 'homely',
    ],
    'login' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'remember_me_text' => 'text',
                'button_name' => 'text',
                'border_text' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
                'sub_title.*' => 'required|max:500',
                'remember_me_text.*' => 'required|max:100',
                'button_name.*' => 'required|max:100',
                'border_text.*' => 'required|max:100',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
            ]
        ],
        'preview' =>[
            'Homely Theme Image' => 'assets/preview/homely/login.png'
        ],
        'theme' => 'homely',
    ],
    'sign_in' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'agree_terms_text' => 'text',
                'button_name' => 'text',
                'border_text' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
                'sub_title.*' => 'required|max:100',
                'agree_terms_text.*' => 'required|max:100',
                'button_name.*' => 'required|max:100',
                'border_text.*' => 'required|max:100',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
            ]
        ],
        'preview' =>[
            'Homely Theme Image' => 'assets/preview/homely/sign-up.png'
        ],
        'theme' => 'homely',
    ],
    'categories' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'button_text' => 'text',
            ],
            'validation' => [
                'heading.*' => 'required|max:300',
                'button_text.*' => 'required|max:100',
            ]
        ],
        'preview' => [
            'Homely Theme Categories Image' => 'assets/preview/homely/categories.png'
        ],
        'theme' => 'homely'
    ],
    'cta_section' =>[
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'sub_heading' => 'text',
                'button_text' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'heading.*' => 'required|max:300',
                'sub_heading.*' => 'required|max:1000',
                'button_text.*' => 'required|max:100',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
            ]
        ],
        'preview' => [
            'Homely Theme CTA Section Image' => 'assets/preview/homely/cta_section.png'
        ],
        'theme' => 'homely'
    ],
    'contact' => [
        'single' => [
            'field_name' => [
                'send_message_title' => 'text',
                'send_message_text' => 'text',
                'agree_with_term_text' => 'text',
                'submit_button' => 'text',
                'map_link' => 'url',
                'background_image' => 'file',
            ],
            'validation' => [
                'send_message_title.*' => 'required|max:100',
                'send_message_text.*' => 'required|max:100',
                'agree_with_term_text.*' => 'required|max:200',
                'submit_button.*' => 'required|max:100',
                'map_link.*' => 'required|max:500',
                'background_image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',

            ]
        ],
        'multiple' => [
            'field_name' => [
                'icon_class' => 'text',
                'title' => 'text',
                'value' => 'text',
            ],
            'validation' => [
                'icon_class.*' => 'required|max:100',
                'title.*' => 'required|max:100',
                'value.*' => 'required|max:100',
            ]
        ],
        'preview' => [
            'Homely Theme Contact Image' => 'assets/preview/homely/contact.png'
        ],
        'theme' => 'homely'
    ],
    'blog' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
            ]
        ],
        'preview' =>[
            'Homely Theme Image' => 'assets/preview/homely/blog.png'
        ],
        'theme' => 'homely',
    ],
    'popular' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
            ]
        ],
        'preview' =>[
            'Homely Theme Popular Image' => 'assets/preview/homely/popular.png'
        ],
        'theme' => 'homely',
    ],
    'property' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'button_name' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:300',
                'button_name.*' => 'required|max:100',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
            ]
        ],
        'preview' => [
            'Homely Theme Property Image' => 'assets/preview/homely/property.png'
        ],
        'theme' => 'homely'
    ],
    'private' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
                'sub_title.*' => 'required|max:500',
            ]
        ],
        'preview' =>[
            'Homely Theme Image' => 'assets/preview/homely/private.png'
        ],
        'theme' => 'homely',
    ],
    'offer' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
            ]
        ],'multiple' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'button_name' => 'text',
                'my_link' => 'url',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:400',
                'sub_title.*' => 'required|max:700',
                'button_name.*' => 'required|max:100',
                'my_link.*' => 'required|max:100',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
            ]
        ],
        'preview' =>[
            'Homely Theme Offer Image' => 'assets/preview/homely/offer.png'
        ],
        'theme' => 'homely',
    ],
    'services' => [
        'multiple' => [
            'field_name' => [
                'title' => 'text',
                'description' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:400',
                'description.*' => 'required|max:700',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
            ]
        ],
        'preview' => [
            'Homely Theme Services Image' => 'assets/preview/homely/services.png'
        ],
        'theme' => 'homely'
    ],
    'banner_three' => [
        'multiple' => [
            'field_name' => [
                'title_part_one' => 'text',
                'title_part_two' => 'text',
                'description' => 'text',
                'price' => 'text',
                'duration' => 'text',
                'duration_text' => 'text',
                'banner_progress' => 'text',
                'background_image' => 'file',
            ],
            'validation' => [
                'title_part_one.*' => 'required|max:100',
                'title_part_two.*' => 'required|max:100',
                'description.*' => 'required|max:700',
                'price.*' => 'required|max:700',
                'duration.*' => 'required|max:700',
                'duration_text.*' => 'required|max:700',
                'banner_progress.*'  => 'required|integer|min:1|max:100',
                'background_image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
            ]
        ],
        'preview' => [
            'Homely Theme Banner Three Image' => 'assets/preview/homely/banner_three.png'
        ],
        'theme' => 'homely'
    ],
    'destination_search' => [
        'single' => [
            'field_name' => [
                'search_text_one' => 'text',
                'search_text_two' => 'text',
                'search_text_three' => 'text',
                'search_button' => 'text'
            ],
            'validation' => [
                'search_text_one.*' => 'required|max:700',
                'search_text_two.*' => 'required|max:700',
                'search_text_three.*' => 'required|max:700',
                'search_button.*' => 'required|max:700'
            ]
        ],
        'preview' => [
            'Homely Theme Destination Search Image' => 'assets/preview/homely/destination_search.png'
        ],
        'theme' => 'homely'
    ],
    'destination_search_four' => [
        'single' => [
            'field_name' => [
                'location_title' => 'text',
            ],
            'validation' => [
                'location_title.*' => 'required|max:500',
            ]
        ],
        'preview' => [
            'Homely Theme Destination Search Four Image' => 'assets/preview/homely/destination_search_four.png'
        ],
        'theme' => 'homely'
    ],
    'footer' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'sub_heading' => 'text',
                'copyright_text' => 'text',
                'newsletter_title' => 'text',
                'newsletter_sub_title' => 'text',
                'newsletter_button' => 'text',
                'background_image' => 'file',
            ],
            'validation' => [
                'heading.*' => 'required|max:100',
                'sub_heading.*' => 'required|max:300',
                'copyright_text.*' => 'required|max:500',
                'newsletter_title.*' => 'required|max:500',
                'newsletter_sub_title.*' => 'required|max:500',
                'newsletter_button.*' => 'required|max:500',
                'background_image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Homely Theme Footer Image' => 'assets/preview/homely/footer.png'
        ],
        'theme' => 'all'
    ],
    'social' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'name' => 'text',
                'icon' => 'icon',
                'link' => 'url',
            ],
            'validation' => [
                'name.*' => 'required|max:300',
                'icon.*' => 'required|max:100',
                'link.*' => 'required|max:1000'
            ]
        ],
        'preview' => [
            'Homely Theme Social Image' => 'assets/preview/homely/social.png'
        ],
        'theme' => 'all'
    ],
    'trending' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
            ],
            'validation' => [
                'heading.*' => 'required|max:300',
            ]
        ],
        'preview' => [
            'Homely Theme Trending Image' => 'assets/preview/homely/trending.png'
        ],
        'theme' => 'homely'
    ],
    'testimonial' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'name' => 'text',
                'designation' => 'text',
                'description' => 'textarea',
                'image' => 'file',
                'shape_image' => 'file',
            ],
            'validation' => [
                'name.*' => 'required|max:100',
                'designation.*' => 'required|max:300',
                'description.*' => 'required|max:2000',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
                'shape_image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
            ]
        ],
        'preview' => [
            'Homely Theme Testimonial Image' => 'assets/preview/homely/testimonial.png'
        ],
        'theme' => 'homely'
    ],
    'team' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
            ],
            'validation' => [
                'heading.*' => 'required|max:300',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'name' => 'text',
                'designation' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'name.*' => 'required|max:100',
                'designation.*' => 'required|max:300',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
            ]
        ],
        'preview' => [
            'Homely Theme Team Image' => 'assets/preview/homely/team.png'
        ],
        'theme' => 'homely'
    ],
    'about' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'description' => 'textarea',
                'image' => 'file',
                'image_two' => 'file',
                'image_three' => 'file',
                'shape_image' => 'file',
                'background_image' => 'file',
            ],
            'validation' => [
                'heading.*' => 'required|max:300',
                'description.*' => 'required|max:2000',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
                'image_two.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
                'image_three.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
                'shape_image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
                'background_image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
            ]
        ],
        'preview' => [
            'Homely Theme About Image' => 'assets/preview/homely/about.png'
        ],
        'theme' => 'homely'
    ],
    'about_two' => [
        'single' => [
            'field_name' => [
                'heading' => 'text',
                'title' => 'text',
                'description' => 'textarea',
                'button_name' => 'text',
                'my_link' => 'url',
                'image' => 'file',
            ],
            'validation' => [
                'heading.*' => 'required|max:300',
                'title.*' => 'required|max:500',
                'description.*' => 'required|max:2000',
                'button_name.*' => 'required|max:500',
                'my_link.*' => 'required|max:2000',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
            ]
        ],
        'preview' => [
            'Homely Theme About Two Image' => 'assets/preview/homely/about_two.png'
        ],
        'theme' => 'homely'
    ],
    'companies' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'image' => 'file',
            ],
            'validation' => [
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
            ]
        ],
        'preview' => [
            'Homely Theme Companies Image' => 'assets/preview/homely/companies.png'
        ],
        'theme' => 'homely'
    ],
    'counter' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'counter_aft' => 'text',
                'counter_data' => 'text',
                'description' => 'text',
            ],
            'validation' => [
                'counter_aft.*' => 'required|max:100',
                'counter_data.*' => 'required|max:100',
                'description.*' => 'required|max:100',
            ]
        ],
        'preview' => [
            'Homely Theme Counter Image' => 'assets/preview/homely/counter.png'
        ],
        'theme' => 'homely'
    ],
    'releted' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
            ]
        ],
        'preview' =>[
            'Homely Theme Vacation two Image' => 'assets/preview/homely/related.png'
        ],
        'theme' => 'homely',
    ],
    'service_three' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'title' => 'text',
                'description' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:400',
                'description.*' => 'required|max:700',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
            ]
        ],
        'preview' => [
            'Homely Theme Service Three Image' => 'assets/preview/homely/service_three.png'
        ],
        'theme' => 'homely'
    ],
    'solution' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'our_name' => 'text',
                'other_name' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
                'our_name.*' => 'required|max:100',
                'other_name.*' => 'required|max:100',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'title' => 'text',
                'description' => 'text'
            ],
            'validation' => [
                'title.*' => 'required|max:400',
                'description.*' => 'required|max:700',
            ]
        ],
        'preview' => [
            'Homely Theme Solution Image' => 'assets/preview/homely/solution.png'
        ],
        'theme' => 'homely'
    ],
    'personal_info' => [
        'multiple' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
                'sub_title.*' => 'required|max:300',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
            ]
        ],
        'preview' =>[
            'Homely Theme User Personal info Image' => 'assets/preview/homely/personal_info.png'
        ],
        'theme' => 'all',
    ],
    'profile_contents' => [
        'single' => [
            'field_name' => [
                'login_security_text' => 'text',
                'login_security_description' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'login_security_text.*' => 'required|max:100',
                'login_security_description.*' => 'required|max:500',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
            ]
        ],
        'preview' =>[
            'Homely Theme Profile contents Image' => 'assets/preview/homely/profile_contents.png'
        ],
        'theme' => 'all',
    ],
    'faq' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
                'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'question' => 'text',
                'answer' => 'text',
            ],
            'validation' => [
                'question.*' => 'required|max:100',
                'answer.*' => 'required|max:300',
            ]
        ],
        'preview' =>[
            'Homely Theme FAQ Image' => 'assets/preview/homely/faq.png'
        ],
        'theme' => 'homely',
    ],
    'escape' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
            ]
        ],
        'preview' => [
            'Homely Theme Escape Image' => 'assets/preview/homely/escape.png'
        ],
        'theme' => 'homely'
    ],

    'property_list_introduction' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'list_title_one' => 'text',
                'list_description_one' => 'text',
                'image_one' => 'file',
                'list_title_two' => 'text',
                'list_description_two' => 'text',
                'image_two' => 'file',
                'list_title_three' => 'text',
                'list_description_three' => 'text',
                'image_three' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'list_title_one.*' => 'required|max:300',
                'list_description_one.*' => 'required|max:500',
                'image_one.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
                'list_title_two.*' => 'required|max:300',
                'list_description_two.*' => 'required|max:500',
                'image_two.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
                'list_title_three.*' => 'required|max:300',
                'list_description_three.*' => 'required|max:500',
                'image_three.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
            ]
        ],
        'preview' => [
            'Homely Theme Property Listing Image' => 'assets/preview/homely/property_list_introduction.png'
        ],
        'theme' => 'homely'
    ],
    'affiliate_page' => [
        'single' => [
            'field_name' => [
                'title_one' => 'text',
                'sub_title_one' => 'text',
                'image_one' => 'file',
                'title_two' => 'text',
                'sub_title_two' => 'text',
                'item_title_one' => 'text',
                'item_description_one' => 'text',
                'item_title_two' => 'text',
                'item_description_two' => 'text',
                'item_title_three' => 'text',
                'item_description_three' => 'text',
                'item_title_four' => 'text',
                'item_description_four' => 'text',
                'title_three' => 'text',
                'sub_title_three' => 'text',
                'title_four' => 'text',
                'sub_title_four' => 'text',
                'image_two' => 'file',
                'image_three' => 'file',
            ],
            'validation' => [
                'title_one.*' => 'required|max:300',
                'sub_title_one.*' => 'required|max:500',
                'image_one.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
                'title_two.*' => 'required|max:300',
                'sub_title_two.*' => 'required|max:500',
                'item_title_one.*' => 'required|max:300',
                'item_description_one.*' => 'required|max:500',
                'item_title_two.*' => 'required|max:300',
                'item_description_two.*' => 'required|max:500',
                'item_title_three.*' => 'required|max:300',
                'item_description_three.*' => 'required|max:500',
                'item_title_four.*' => 'required|max:300',
                'item_description_four.*' => 'required|max:500',
                'title_three.*' => 'required|max:300',
                'sub_title_three.*' => 'required|max:500',
                'title_four.*' => 'required|max:300',
                'sub_title_four.*' => 'required|max:500',
                'image_two.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
                'image_three.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'serial' => 'text',
                'title' => 'text',
                'sub_title' => 'text',
            ],
            'validation' => [
                'serial.*' => 'required|max:10',
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:500',
            ]
        ],
        'preview' => [
            'Homely Theme Affiliate Page Image' => 'assets/preview/homely/affiliate_page.png'
        ],
        'theme' => 'homely'
    ],
    'affiliate_login' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'remember_me_text' => 'text',
                'button_name' => 'text',
                'forget_text' => 'text',
                'image_one' => 'file',
                'image_two' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
                'sub_title.*' => 'required|max:100',
                'remember_me_text.*' => 'required|max:100',
                'button_name.*' => 'required|max:100',
                'forget_text.*' => 'required|max:100',
                'image_one.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
                'image_two.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
            ]
        ],
        'preview' =>[
            'Homely Theme Image' => 'assets/preview/homely/affiliate_login.png'
        ],
        'theme' => 'homely',
    ],
    'affiliate_register' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'remember_me_text' => 'text',
                'button_name' => 'text',
                'forget_text' => 'text',
                'image_one' => 'file',
                'image_two' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:100',
                'sub_title.*' => 'required|max:100',
                'remember_me_text.*' => 'required|max:100',
                'button_name.*' => 'required|max:100',
                'forget_text.*' => 'required|max:100',
                'image_one.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
                'image_two.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,svg',
            ]
        ],
        'preview' =>[
            'Homely Theme Image' => 'assets/preview/homely/affiliate_register.png'
        ],
        'theme' => 'homely',
    ],

    'message' => [
        'required' => 'This field is required.',
        'min' => 'This field must be at least :min characters.',
        'max' => 'This field may not be greater than :max characters.',
        'image' => 'This field must be image.',
        'mimes' => 'This image must be a file of type: jpg, jpeg, png.',
        'integer' => 'This field must be an integer value',
    ],
    'content_media' => [
        'image' => 'file',
        'image_one' => 'file',
        'image_two' => 'file',
        'image_three' => 'file',
        'background_image' => 'file',
        'thumb_image' => 'file',
        'shape_image' => 'file',
        'my_link' => 'url',
        'map_link' => 'url',
        'circle_link' => 'url',
        'button_link' => 'url',
        'video_link' => 'url',
        'video_file' => 'video',
        'icon' => 'icon',
        'count_number' => 'number',
        'start_date' => 'date'
    ]
];

