<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Form\CreateTweetType;
use App\Form\SearchType;
use App\Form\TweetType;
use App\Repository\TweetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/tweet')]
class TweetController extends AbstractController
{
    #[Route('/', name: 'app_tweet_index', methods: ['GET', 'POST'])]
    public function index(TweetRepository $tweetRepository, Request $request): Response
    {
        $form = $this->createForm(SearchType::class, null, ['method' => 'GET']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $search = $data['search'];
            if ($search) {
                $tweets = $tweetRepository->search($search);
            }
        }

        if (!isset($tweets)) {
            $tweets = $tweetRepository->findBy([], ['createdAt' => 'DESC']);
        }

        return $this->render('tweet/index.html.twig', [
            'tweets' => $tweets,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_tweet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TweetRepository $tweetRepository): Response
    {
        $tweet = new Tweet();
        $form = $this->createForm(CreateTweetType::class, $tweet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tweetRepository->save($tweet, true);

            return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tweet/new.html.twig', [
            'tweet' => $tweet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tweet_show', methods: ['GET'])]
    public function show(Tweet $tweet): Response
    {
        return $this->render('tweet/show.html.twig', [
            'tweet' => $tweet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tweet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tweet $tweet, TweetRepository $tweetRepository): Response
    {
        $form = $this->createForm(TweetType::class, $tweet,
            ['action' => $this->generateUrl('app_tweet_edit', ['id' => $tweet->getId()])]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tweetRepository->save($tweet, true);

            return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tweet/edit.html.twig', [
            'tweet' => $tweet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/modal-edit', name: 'app_tweet_modaledit', methods: ['GET', 'POST'])]
    public function modalEdit(Request $request, Tweet $tweet, TweetRepository $tweetRepository): Response
    {
        $form = $this->createForm(TweetType::class, $tweet,
            ['action' => $this->generateUrl('app_tweet_modaledit', ['id' => $tweet->getId()])]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tweetRepository->save($tweet, true);

            return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tweet/modal_edit.html.twig', [
            'tweet' => $tweet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/stats', name: 'app_tweet_stats', methods: ['GET'])]
    public function stats(Tweet $tweet): Response
    {
        sleep(1); // simulate a slow response

        return $this->render('tweet/stats.html.twig', [
            'tweet' => $tweet,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_tweet_delete', methods: ['POST'])]
    public function delete(Request $request, Tweet $tweet, TweetRepository $tweetRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tweet->getId(), $request->request->get('_token'))) {
            $id = $tweet->getId();
            $tweetRepository->remove($tweet, true);

            sleep(1);

            // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

            return $this->render('tweet/_delete.stream.html.twig', [
                'id' => $id,
            ]);
        }

        return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
    }
}
