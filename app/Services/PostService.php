<?php
namespace App\Services;

use App\Http\Requests\PostSearchRequest;
use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use App\Repositories\PostRepository;
use App\Repositories\PostRepositoryInterface;
use App\Services\Snippets\AccordionService;
use App\Services\Snippets\GalleryMasonryService;
use Illuminate\Support\Collection;

class PostService
{
    private PostRepository $postRepository;
    private AccordionService $accordionService;
    private GalleryMasonryService $galleryService;
    private GlossaryService $glossaryService;

    /**
     * PostService constructor.
     *
     * @param \App\Repositories\PostRepository $postRepository
     * @param \App\Services\Snippets\AccordionService $accordionService
     * @param \App\Services\Snippets\GalleryMasonryService $galleryService
     * @param \App\Services\GlossaryService $glossaryService
     */
    public function __construct(
        PostRepository $postRepository,
        AccordionService $accordionService,
        GalleryMasonryService $galleryService,
        GlossaryService $glossaryService
    ) {
        $this->postRepository = $postRepository;
        $this->accordionService = $accordionService;
        $this->galleryService = $galleryService;
        $this->glossaryService = $glossaryService;
    }

    /**
     * Create a post
     *
     * @param \App\Http\Requests\PostStoreRequest $request
     *
     * @return \App\Models\Post
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function createPost(PostStoreRequest $request): Post
    {
        $post = $this->postRepository->store($request->all());
        $this->storeImages($post, $request);

        return $post;
    }

    /**
     * Update the Post
     *
     * @param \App\Http\Requests\PostStoreRequest $request
     * @param int $postId
     *
     * @return \App\Models\Post
     */
    public function updatePost(PostStoreRequest $request, int $postId): Post
    {
        $post = $this->postRepository->update($request->all(), $postId);
        $this->storeImages($post, $request);

        return $post;
    }

    /**
     * Return the post from the database
     *
     * @param $postId
     *
     * @return \App\Models\Post
     */
    public function getById(int $postId): Post
    {
        return $this->postRepository->getById($postId);
    }

    /**
     * Get all the Posts.
     *
     * @param int|null $recordsPerPage
     * @param array|null $searchParameters
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPosts(int $recordsPerPage = null, array $searchParameters = null)
    {
        return $this->postRepository->getAll($recordsPerPage, $searchParameters);
    }

    /**
     * Delete the post from the database
     *
     * @param int $postId
     */
    public function deletePost(int $postId): void
    {
        $this->postRepository->delete($postId);
    }

    /**
     * Returns the post body adding support to transform snippets to html code
     * eg. Accordion, Gallery, Glossary
     *
     * @param $post
     *
     * @return string
     */
    public function getPostBody($post): string
    {
        //dd($post->getTranslation('body', 'en'));

        $postBody = $post->body;

        $postBody = $this->accordionService->snippetsToHTML($postBody);
        $postBody = $this->galleryService->snippetsToHTML($postBody, $post);
        $postBody = $this->glossaryService->markGlossaryTerms($postBody);

        return $postBody;
    }

    /**
     * Get the number of post created in the last 30 days.
     *
     * @return int
     */
    public function getNumberPostsCreatedLastThirtyDays(): int
    {
        return Post::whereDate('created_at', '>', date('Y-m-d', strtotime('-30 days')))->count();
    }

    /**
     * Store the uploaded photos in the Spatie Media Library
     *
     * @param \App\Models\Post $post
     * @param \App\Http\Requests\PostStoreRequest $request
     *
     * @return void
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    private function storeImages(Post $post, PostStoreRequest $request): void
    {
        if ($request->file('introimage')) {
            $introimage = $request->file('introimage');
            if ($introimage->isValid()) {
                $post->addMedia($introimage)->toMediaCollection('introimage');
            }
        }

        if ($request->file('images')) {
            foreach ($request->file('images') as $photo) {
                if ($photo->isValid()) {
                    $post->addMedia($photo)->toMediaCollection('images');
                }
            }
        }

        if ($request->introimage_delete == 'true') {
            $mediaItems = $post->getMedia('introimage');
            if (!is_null($mediaItems[0])) {
                $mediaItems[0]->delete();
            }
        }
    }

    /**
     * Return an array with the thumb images ulrs
     *
     * @param int $postId
     *
     * @return array
     */
    public function getThumbsUrls(int $postId): array
    {
        $thumbUrls = [];

        $post = $this->getById($postId);
        foreach ($post->getMedia('post') as $photo) {
            $thumbUrls[] = $photo->getUrl('thumb');
        }

        return $thumbUrls;
    }

    /**
     * Get the post search parameters
     *
     * @param \App\Http\Requests\PostSearchRequest $request
     *
     * @return array
     */
    public function getSearchParameters(PostSearchRequest $request): array
    {
        $searchParameters = [];
        $searchParameters['title'] = $request->title ?? null;
        $searchParameters['categoryId'] = $request->categoryId ?? null;
        $searchParameters['startDate'] = $request->startDate ?? null;
        $searchParameters['endDate'] = $request->endDate ?? null;
        $searchParameters['status'] = $request->status ?? null;

        return $searchParameters;
    }
}
